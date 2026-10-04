<?php
// Yeni başvuruyu e-posta ile bildirir. Ayarlar (açık/kapalı, alıcılar, SMTP) api/mail_config.php içinde.

function leadMailHeaderSafe($value) {
    // Başlık enjeksiyonunu engelle (CR/LF temizliği)
    return trim(preg_replace('/[\r\n]+/', ' ', (string) $value));
}

/** SMTP yanıtını oku; beklenen kodla başlamıyorsa exception fırlat. */
function leadSmtpExpect($fp, $codes) {
    $response = '';
    while (($line = fgets($fp, 515)) !== false) {
        $response .= $line;
        if (strlen($line) < 4 || $line[3] === ' ') break; // çok satırlı yanıtın son satırı
    }
    $code = (int) substr($response, 0, 3);
    if (!in_array($code, (array) $codes, true)) {
        throw new RuntimeException("SMTP beklenmeyen yanıt: " . trim($response));
    }
    return $response;
}

function leadSmtpCommand($fp, $command, $codes) {
    fwrite($fp, $command . "\r\n");
    return leadSmtpExpect($fp, $codes);
}

/** Basit SMTP istemcisi (STARTTLS/SSL ve AUTH LOGIN destekli). */
function leadSmtpSend(array $smtp, $from, array $recipients, $message) {
    $secure = strtolower($smtp['secure']);
    $remote = ($secure === 'ssl' ? 'ssl://' : 'tcp://') . $smtp['host'] . ':' . (int) $smtp['port'];
    $fp = @stream_socket_client($remote, $errno, $errstr, 15);
    if (!$fp) throw new RuntimeException("SMTP bağlantısı kurulamadı ($remote): $errstr");
    stream_set_timeout($fp, 15);
    try {
        $helo = preg_replace('/[^a-z0-9.-]/i', '', $_SERVER['HTTP_HOST'] ?? 'localhost') ?: 'localhost';
        leadSmtpExpect($fp, 220);
        leadSmtpCommand($fp, "EHLO $helo", 250);
        if ($secure === 'tls') {
            leadSmtpCommand($fp, "STARTTLS", 220);
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                throw new RuntimeException("SMTP STARTTLS başarısız");
            }
            leadSmtpCommand($fp, "EHLO $helo", 250);
        }
        if ($smtp['user'] !== '') {
            leadSmtpCommand($fp, "AUTH LOGIN", 334);
            leadSmtpCommand($fp, base64_encode($smtp['user']), 334);
            leadSmtpCommand($fp, base64_encode($smtp['pass']), 235);
        }
        leadSmtpCommand($fp, "MAIL FROM:<$from>", 250);
        foreach ($recipients as $rcpt) {
            leadSmtpCommand($fp, "RCPT TO:<$rcpt>", [250, 251]);
        }
        leadSmtpCommand($fp, "DATA", 354);
        // Nokta ile başlayan satırları kaçır (dot-stuffing)
        $data = preg_replace('/^\./m', '..', $message);
        leadSmtpCommand($fp, $data . "\r\n.", 250);
        leadSmtpCommand($fp, "QUIT", 221);
    } finally {
        fclose($fp);
    }
}

/**
 * @param array $lead Ham (HTML-escape edilmemiş) form alanları
 * @return bool Mail gönderildiyse true; kapalıysa veya hata olduysa false
 */
function sendLeadMail(array $lead) {
    $configFile = __DIR__ . '/mail_config.php';
    if (!file_exists($configFile)) return false; // Henüz ayarlanmadı: sessizce atla
    $mailEnabled = false;
    $mailTo = [];
    $mailCc = [];
    $mailFrom = '';
    $smtpHost = '';
    $smtpPort = 587;
    $smtpSecure = 'tls';
    $smtpUser = '';
    $smtpPass = '';
    require $configFile;

    if (!$mailEnabled) return false; // Mail gönderimi kapalı

    $toList = array_values(array_filter(array_map('trim', (array) $mailTo), function ($a) { return filter_var($a, FILTER_VALIDATE_EMAIL); }));
    $ccList = array_values(array_filter(array_map('trim', (array) $mailCc), function ($a) { return filter_var($a, FILTER_VALIDATE_EMAIL); }));
    if (!$toList) {
        logError("Mail gönderilmedi: mail_config.php içinde geçerli alıcı yok.");
        return false;
    }
    $to = implode(', ', $toList);
    $cc = implode(', ', $ccList);

    $host = preg_replace('/^www\./', '', preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? 'localhost'));
    $from = filter_var($mailFrom, FILTER_VALIDATE_EMAIL) ? $mailFrom
          : (filter_var($smtpUser, FILTER_VALIDATE_EMAIL) ? $smtpUser : 'noreply@' . $host);

    $officeLabel = $lead['office'] !== '' ? $lead['office'] : 'Global';
    $subject = 'Yeni iş ortaklığı başvurusu: ' . leadMailHeaderSafe($lead['name']) . ' (' . leadMailHeaderSafe($officeLabel) . ')';

    $rows = [
        'Rol'              => $lead['role'],
        'Ad Soyad'         => $lead['name'],
        'E-posta'          => $lead['email'],
        'Telefon'          => $lead['phone'],
        'Aylık yönlendirme'=> $lead['volume'],
        'Bizi nereden duydu' => $lead['source'],
        'Mesaj'            => $lead['message'],
        'Ofis sayfası'     => $officeLabel,
        'Sayfa dili'       => $lead['lang'],
        'Sayfa'            => $lead['url'],
        'Referrer'         => $lead['referrer'],
        'utm_source'       => $lead['utm_source'],
        'utm_medium'       => $lead['utm_medium'],
        'utm_campaign'     => $lead['utm_campaign'],
        'utm_content'      => $lead['utm_content'],
        'utm_term'         => $lead['utm_term'],
        'gclid'            => $lead['gclid'],
        'Gönderim zamanı'  => gmdate('Y-m-d H:i:s') . ' UTC',
    ];
    $body = "Partner portalından yeni bir başvuru geldi.\n\n";
    foreach ($rows as $label => $value) {
        if ($value === '' || $value === null) continue;
        $label .= ':';
        $body .= $label . str_repeat(' ', max(1, 22 - mb_strlen($label, 'UTF-8'))) . $value . "\n";
    }

    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $encodedBody = rtrim(chunk_split(base64_encode($body), 76, "\r\n"));
    $headers = [
        'From: =?UTF-8?B?' . base64_encode('Acıbadem Partner Portal') . '?= <' . $from . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: base64',
    ];
    if ($cc !== '') $headers[] = 'Cc: ' . $cc;
    if (filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . leadMailHeaderSafe($lead['email']);
    }

    if (trim($smtpHost) === '') {
        // SMTP ayarlanmadıysa sunucunun kendi mail() fonksiyonu kullanılır
        $ok = @mail($to, $encodedSubject, $encodedBody, implode("\r\n", $headers));
        if (!$ok) logError("Mail gönderilemedi (mail() false döndü). Alıcı: " . $to);
        return $ok;
    }

    $message = implode("\r\n", array_merge([
        'Date: ' . date('r'),
        'To: ' . $to,
        'Subject: ' . $encodedSubject,
        'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . $host . '>',
    ], $headers)) . "\r\n\r\n" . $encodedBody;

    try {
        leadSmtpSend([
            'host' => trim($smtpHost), 'port' => $smtpPort, 'secure' => (string) $smtpSecure,
            'user' => (string) $smtpUser, 'pass' => (string) $smtpPass,
        ], $from, array_merge($toList, $ccList), $message);
        return true;
    } catch (Throwable $e) {
        logError("SMTP Hatası: " . $e->getMessage());
        return false;
    }
}
