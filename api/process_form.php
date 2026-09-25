<?php
ini_set('display_errors', 0); // Hataları ekrana basmayı gizle

// Kapalı kapılar ardında ne hata olduğunu görebilmemiz için log fonksiyonu
function logError($msg) {
    file_put_contents(__DIR__ . '/db_error_log.txt', date('Y-m-d H:i:s') . " - " . $msg . "\n", FILE_APPEND);
}

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // JSON body oku (fetch() application/json ile gönderiyor)
    $raw  = file_get_contents("php://input");
    $data = json_decode($raw, true) ?? [];

    // Honeypot kontrolü (Bot engelleme)
    if (!empty($data['company'])) {
        echo json_encode(["status" => "ok"]);
        exit;
    }

    function escapeInput($input) {
        return htmlspecialchars(trim($input ?? ""), ENT_QUOTES, "UTF-8");
    }

    // JSON verisinden alanları çek
    $role         = escapeInput($data["role"]         ?? "");
    $name         = escapeInput($data["name"]         ?? "");
    $email        = escapeInput($data["email"]        ?? "");
    $volume       = escapeInput($data["volume"]       ?? "");
    $message      = escapeInput($data["message"]      ?? "");
    $country_code = escapeInput($data["country_code"] ?? "");
    $phone_raw    = escapeInput($data["phone"]        ?? "");
    
    // Country code ve telefonu tek bir alanda birleştiriyoruz
    $phone        = trim($country_code . " " . $phone_raw);

    $url = $_SERVER["HTTP_REFERER"] ?? "https://partner.acibademinternational.com/london/";

    require_once "db_connect.php";

    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8mb4");

        if ($conn->connect_error) {
            logError("Veritabanı Bağlantı Hatası: " . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("INSERT INTO partnership_applications (role, name, email, phone, volume, message, url) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                logError("SQL Hazırlama Hatası (Tablo eksik/hatalı olabilir): " . $conn->error);
            } else {
                $stmt->bind_param("sssssss", $role, $name, $email, $phone, $volume, $message, $url);

                if (!$stmt->execute()) {
                    logError("Kayıt İşleme Hatası: " . $stmt->error);
                }
                $stmt->close();
            }
            $conn->close();
        }
    } catch (Throwable $e) {
        logError("Kritik PHP Hatası: " . $e->getMessage());
    }

    /* 
    =============================================
    MAİL FONKSİYONU ŞİMDİLİK DEVRE DIŞI BIRAKILDI
    =============================================
    if (file_exists("./mail_test/require.php")) {
        require_once "./mail_test/require.php";
        // ... Mail kodları ...
    }
    */

    // Başarılı yanıt döndür (fetch().then() zinciri bunu bekliyor)
    echo json_encode(["status" => "ok"]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}
?>