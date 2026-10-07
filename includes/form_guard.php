<?php
/*
 * Server-side spam protection for the lead form (no third-party service).
 *
 *  - Signed form token: the page embeds "<time>.<hmac>". A submission without a
 *    valid token did not come from our page; one sent within a few seconds of
 *    the page loading was typed by a bot.
 *  - Per-IP rate limit, stored as small files under /storage.
 *
 * The HMAC secret is generated on first use into storage/form_secret.php, so it
 * never lives in the repository. Everything fails open (the form keeps working)
 * if /storage is not writable; the problem is only logged.
 */

const FG_STORAGE = __DIR__ . '/../storage';
const FG_MIN_SECONDS = 3;            // faster than this is not a human
const FG_MAX_AGE = 60 * 60 * 24 * 2; // tokens from pages open longer are rejected

function fg_log($msg) {
    @file_put_contents(__DIR__ . '/../api/db_error_log.txt', date('Y-m-d H:i:s') . " - FormGuard: " . $msg . "\n", FILE_APPEND);
}

function fg_secret() {
    static $secret = null;
    if ($secret !== null) return $secret;
    $file = FG_STORAGE . '/form_secret.php';
    if (is_file($file)) {
        $secret = (string) require $file;
        if (strlen($secret) >= 32) return $secret;
    }
    $secret = bin2hex(random_bytes(32));
    if (@file_put_contents($file, "<?php return '" . $secret . "';\n", LOCK_EX) === false) {
        // Not writable: fall back to a per-install value so tokens still validate.
        fg_log("storage/ is not writable; using a fallback secret.");
        $secret = hash('sha256', __DIR__ . php_uname() . (string) @filemtime(__FILE__));
    }
    return $secret;
}

/** Token to embed in the form. */
function fg_token() {
    $ts = (string) time();
    return $ts . '.' . hash_hmac('sha256', $ts, fg_secret());
}

/** @return string 'ok', 'fast' (sent too soon) or 'invalid' (missing, forged or expired) */
function fg_check_token($token) {
    if (!is_string($token) || !preg_match('/^(\d{9,11})\.([a-f0-9]{64})$/', $token, $m)) return 'invalid';
    if (!hash_equals(hash_hmac('sha256', $m[1], fg_secret()), $m[2])) return 'invalid';
    $age = time() - (int) $m[1];
    if ($age < 0 || $age > FG_MAX_AGE) return 'invalid';
    if ($age < FG_MIN_SECONDS) return 'fast';
    return 'ok';
}

function fg_client_ip() {
    return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

/**
 * Sliding-window rate limit.
 * @param string $bucket e.g. "form:<ip>"
 * @param array  $limits seconds => max hits, e.g. [3600 => 5, 86400 => 20]
 * @param bool   $record false only checks, without counting this request
 * @return bool true if allowed
 */
function fg_rate_limit($bucket, array $limits, $record = true) {
    $dir = FG_STORAGE . '/ratelimit';
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        fg_log("Cannot create storage/ratelimit; rate limiting disabled.");
        return true;
    }
    $file = $dir . '/' . hash('sha256', $bucket) . '.json';
    $fp = @fopen($file, 'c+');
    if (!$fp) return true;
    try {
        flock($fp, LOCK_EX);
        $hits = json_decode(stream_get_contents($fp), true);
        if (!is_array($hits)) $hits = [];
        $now = time();
        $window = max(array_keys($limits));
        $hits = array_values(array_filter($hits, function ($t) use ($now, $window) { return is_int($t) && $t > $now - $window; }));
        foreach ($limits as $seconds => $max) {
            $count = count(array_filter($hits, function ($t) use ($now, $seconds) { return $t > $now - $seconds; }));
            if ($count >= $max) return false;
        }
        if ($record) {
            $hits[] = $now;
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($hits));
        }
        return true;
    } finally {
        flock($fp, LOCK_UN);
        fclose($fp);
        if (mt_rand(1, 200) === 1) fg_cleanup($dir);
    }
}

/** Count a hit without checking (e.g. a failed login). */
function fg_rate_hit($bucket, $window) {
    fg_rate_limit($bucket, [$window => PHP_INT_MAX], true);
}

/** Remove rate-limit files untouched for two days. */
function fg_cleanup($dir) {
    foreach ((array) glob($dir . '/*.json') as $f) {
        if (@filemtime($f) < time() - 172800) @unlink($f);
    }
}
