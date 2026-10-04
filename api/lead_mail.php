<?php
// Yeni başvuruyu e-posta ile bildirir. Alıcılar api/mail_config.php içinde.

function leadMailHeaderSafe($value) {
    // Başlık enjeksiyonunu engelle (CR/LF temizliği)
    return trim(preg_replace('/[\r\n]+/', ' ', (string) $value));
}

function leadMailAddressList(array $list) {
    $valid = array_filter(array_map('trim', $list), function ($a) {
        return filter_var($a, FILTER_VALIDATE_EMAIL);
    });
    return implode(', ', $valid);
}

/**
 * @param array $lead Ham (HTML-escape edilmemiş) form alanları
 * @return bool mail() başarılıysa true
 */
function sendLeadMail(array $lead) {
    $configFile = __DIR__ . '/mail_config.php';
    if (!file_exists($configFile)) {
        logError("Mail gönderilmedi: api/mail_config.php bulunamadı.");
        return false;
    }
    $mailTo = [];
    $mailCc = [];
    $mailFrom = '';
    require $configFile;

    $to = leadMailAddressList((array) $mailTo);
    if ($to === '') {
        logError("Mail gönderilmedi: mail_config.php içinde geçerli alıcı yok.");
        return false;
    }
    $cc = leadMailAddressList((array) $mailCc);

    $host = preg_replace('/^www\./', '', preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? 'localhost'));
    $from = filter_var($mailFrom, FILTER_VALIDATE_EMAIL) ? $mailFrom : 'noreply@' . $host;

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

    $headers = [
        'From: Acibadem Partner Portal <' . $from . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'X-Mailer: PHP/' . phpversion(),
    ];
    if ($cc !== '') $headers[] = 'Cc: ' . $cc;
    if (filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . leadMailHeaderSafe($lead['email']);
    }

    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $ok = @mail($to, $encodedSubject, $body, implode("\r\n", $headers));
    if (!$ok) logError("Mail gönderilemedi (mail() false döndü). Alıcı: " . $to);
    return $ok;
}
