<?php
// Bu dosya CRON (zamanlanmış görev) ile günde 1 kez çalıştırılacaktır.
// Web üzerinden erişime kapalıdır.
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Access denied.');
}
$url = "https://acibadem.world/api/offices";
$jsonPath = __DIR__ . '/offices.json';

echo "Fetching data from API...\n";
$ctx = stream_context_create(['http' => ['timeout' => 15]]);
$data = @file_get_contents($url, false, $ctx);

if ($data) {
    // Geçerli bir JSON mu kontrol edelim
    $decoded = json_decode($data, true);
    if (isset($decoded['data'])) {
        file_put_contents($jsonPath, $data);
        echo "Successfully updated offices.json.\n";
    } else {
        echo "Invalid JSON format received.\n";
    }
} else {
    echo "Failed to fetch data from API.\n";
}
