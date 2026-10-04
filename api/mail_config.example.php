<?php
// mail_config.example.php
// Copy to mail_config.php (not tracked by git) and fill in the values.

// false: başvurular e-posta ile gönderilmez (sadece veritabanına kaydedilir).
// SMTP bilgilerini girdikten sonra true yapın.
$mailEnabled = false;

// Every new partner application is emailed to these addresses.
$mailTo = ["leads@example.com"];
$mailCc = [];

// Sender address. Leave empty to use the SMTP user (or noreply@<current host>).
$mailFrom = "";

// SMTP server. Leave $smtpHost empty to use PHP's mail() instead.
$smtpHost = "";          // e.g. "smtp.office365.com" or "mail.yourdomain.com"
$smtpPort = 587;         // 587 for "tls", 465 for "ssl"
$smtpSecure = "tls";     // "tls" (STARTTLS), "ssl", or "" for none
$smtpUser = "";
$smtpPass = "";
?>
