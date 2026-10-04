<?php
// mail_config.example.php
// Copy to mail_config.php (not tracked by git) and fill in the recipients.
// Every new partner application is emailed to these addresses.
$mailTo = ["leads@example.com"];
$mailCc = [];
// Sender address. Use an address on this site's own domain so the mail is
// not rejected as spoofed; leave empty to use noreply@<current host>.
$mailFrom = "";
?>
