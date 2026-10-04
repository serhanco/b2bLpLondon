<?php
ini_set('display_errors', 0); // Hataları ekrana basmayı gizle

// Kapalı kapılar ardında ne hata olduğunu görebilmemiz için log fonksiyonu
function logError($msg) {
    file_put_contents(__DIR__ . '/db_error_log.txt', date('Y-m-d H:i:s') . " - " . $msg . "\n", FILE_APPEND);
}

function respond($code, array $body) {
    http_response_code($code);
    echo json_encode($body);
    exit;
}

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    respond(405, ["status" => "error", "message" => "Method not allowed"]);
}

// JSON body oku (fetch() application/json ile gönderiyor)
$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);
if (!is_array($data)) $data = [];

// Honeypot kontrolü (Bot engelleme)
if (!empty($data['company'])) {
    respond(200, ["status" => "ok"]);
}

// Ham değer: kırp, kontrol karakterlerini at (mesaj dışında satır sonlarını da), uzunluğu sınırla
function field($data, $key, $max = 255, $multiline = false) {
    $v = $data[$key] ?? "";
    if (!is_scalar($v)) return "";
    $pattern = $multiline ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u' : '/[\x00-\x1F\x7F]+/u';
    $v = trim(preg_replace($pattern, $multiline ? '' : ' ', (string) $v));
    return mb_substr($v, 0, $max, "UTF-8");
}

$lead = [
    'role'         => field($data, 'role', 100),
    'name'         => field($data, 'name'),
    'email'        => field($data, 'email'),
    'phone'        => field($data, 'phone', 50),
    'volume'       => field($data, 'volume', 50),
    'message'      => field($data, 'message', 5000, true),
    'source'       => field($data, 'source', 100),
    'lang'         => field($data, 'lang', 10),
    'office'       => field($data, 'office', 100),
    'utm_source'   => field($data, 'utm_source'),
    'utm_medium'   => field($data, 'utm_medium'),
    'utm_campaign' => field($data, 'utm_campaign'),
    'utm_content'  => field($data, 'utm_content'),
    'utm_term'     => field($data, 'utm_term'),
    'gclid'        => field($data, 'gclid'),
    'referrer'     => field($data, 'referrer', 1000),
];

// Zorunlu alanlar (tarayıcı tarafı doğrulamasının sunucu kopyası)
if ($lead['role'] === '' || $lead['name'] === '' || $lead['phone'] === ''
    || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
    respond(422, ["status" => "error", "message" => "Missing or invalid fields"]);
}

// Başvurunun geldiği sayfa: önce Referer, yoksa formun bildirdiği yol
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$landing = field($data, 'landing_page', 500);
$lead['url'] = $_SERVER["HTTP_REFERER"]
    ?? ($scheme . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($landing !== '' && $landing[0] === '/' ? $landing : '/'));

// Veritabanında mevcut davranışla uyumlu olsun diye HTML-escape edilmiş değerler saklanıyor
$db = array_map(function ($v) { return htmlspecialchars($v, ENT_QUOTES, "UTF-8"); }, $lead);

$savedToDb = false;
require_once "db_connect.php";

// PHP 8.1+ mysqli varsayılan olarak exception fırlatır; aşağıdaki kontroller false dönüşüne göre yazıldı
mysqli_report(MYSQLI_REPORT_OFF);

try {
    $conn = @new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        logError("Veritabanı Bağlantı Hatası: " . $conn->connect_error);
    } else {
        $conn->set_charset("utf8mb4");
        $columns = ['role', 'name', 'email', 'phone', 'volume', 'message', 'url', 'source', 'lang', 'office',
                    'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'referrer'];
        $sql = "INSERT INTO partnership_applications (" . implode(', ', $columns) . ") VALUES ("
             . implode(', ', array_fill(0, count($columns), '?')) . ")";
        $stmt = $conn->prepare($sql);
        $values = array_map(function ($c) use ($db) { return $db[$c]; }, $columns);

        if (!$stmt) {
            // Yeni kolonlar henüz eklenmediyse (migration çalıştırılmadıysa) eski şemaya kaydet
            logError("Genişletilmiş INSERT hazırlanamadı, eski şemaya dönülüyor (database_migrations klasörüne bakın): " . $conn->error);
            $columns = ['role', 'name', 'email', 'phone', 'volume', 'message', 'url'];
            $legacyMessage = $db['message'] . ($db['source'] !== '' ? ($db['message'] !== '' ? ' | ' : '') . 'Source: ' . $db['source'] : '');
            $values = [$db['role'], $db['name'], $db['email'], $db['phone'], $db['volume'], $legacyMessage, $db['url']];
            $stmt = $conn->prepare("INSERT INTO partnership_applications (role, name, email, phone, volume, message, url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        }

        if (!$stmt) {
            logError("SQL Hazırlama Hatası (Tablo eksik/hatalı olabilir): " . $conn->error);
        } else {
            $stmt->bind_param(str_repeat('s', count($values)), ...$values);
            if ($stmt->execute()) {
                $savedToDb = true;
            } else {
                logError("Kayıt İşleme Hatası: " . $stmt->error);
            }
            $stmt->close();
        }
        $conn->close();
    }
} catch (Throwable $e) {
    logError("Kritik PHP Hatası: " . $e->getMessage());
}

// Veritabanı başarısız olsa bile başvuru e-posta ile ulaşsın
require_once __DIR__ . "/lead_mail.php";
$mailed = false;
try {
    $mailed = sendLeadMail($lead);
} catch (Throwable $e) {
    logError("Mail Hatası: " . $e->getMessage());
}

if (!$savedToDb && !$mailed) {
    // İkisi de başarısızsa kullanıcıya hata kutusunu (iletişim bilgileriyle) göster
    respond(500, ["status" => "error"]);
}

// Başarılı yanıt döndür (fetch().then() zinciri bunu bekliyor)
respond(200, ["status" => "ok"]);
