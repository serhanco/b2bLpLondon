<?php
// CRON (CLI) veya token ile korunan HTTP çağrısı ile günde 1 kez çalıştırılır.
// Token kodda tutulmaz: api/sync_config.php (gitignore'da) içinden okunur.

if (php_sapi_name() !== 'cli') {
    $configFile = __DIR__ . '/sync_config.php';
    $config = is_file($configFile) ? require $configFile : [];
    $secretToken = $config['sync_token'] ?? '';
    $given = isset($_GET['token']) ? (string)$_GET['token'] : '';

    // Fail closed: config yoksa / token boş ya da placeholder ise hiçbir çağrı kabul edilmez
    if ($secretToken === '' || $secretToken === 'CHANGE_ME' || !hash_equals($secretToken, $given)) {
        http_response_code(403);
        exit('Access denied.');
    }
    header('Content-Type: text/plain; charset=utf-8');
}

$url = "https://acibadem.world/api/offices";
$jsonPath = __DIR__ . '/offices.json';

echo "Fetching data from API...\n";
$ctx = stream_context_create(['http' => ['timeout' => 15]]);
$data = @file_get_contents($url, false, $ctx);

if (!$data) {
    http_response_code(502);
    exit("Failed to fetch data from API.\n");
}

$decoded = json_decode($data, true);
if (!isset($decoded['data']) || !is_array($decoded['data']) || count($decoded['data']) === 0) {
    http_response_code(502);
    exit("Invalid or empty JSON received. Existing offices.json kept.\n");
}

// Atomic write: önce geçici dosyaya yaz, sonra yer değiştir (yarım yazılmış JSON riski yok)
$tmp = $jsonPath . '.tmp';
if (file_put_contents($tmp, $data, LOCK_EX) === false || !rename($tmp, $jsonPath)) {
    @unlink($tmp);
    http_response_code(500);
    exit("Failed to write offices.json.\n");
}
echo "Successfully updated offices.json (" . count($decoded['data']) . " offices).\n";
