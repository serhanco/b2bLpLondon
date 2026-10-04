<?php
/*
 * Language selection and translation helpers.
 *
 * Order of precedence for the page language:
 *   1. ?lang=xx in the URL (also remembered in a cookie)
 *   2. the "lang" cookie from an earlier explicit choice
 *   3. the browser's Accept-Language header
 *   4. English
 *
 * Page copy lives in /lang/<code>.php; each file returns an array of
 * key => string. Missing keys fall back to English.
 */

const I18N_DEFAULT = 'en';
const I18N_COOKIE = 'lang';

// code => [native name, html lang attribute, text direction, flag-icons country code]
const I18N_LANGUAGES = [
    'en' => ['English',         'en',      'ltr', 'gb'],
    'tr' => ['Türkçe',          'tr',      'ltr', 'tr'],
    'de' => ['Deutsch',         'de',      'ltr', 'de'],
    'fr' => ['Français',        'fr',      'ltr', 'fr'],
    'ru' => ['Русский',         'ru',      'ltr', 'ru'],
    'uk' => ['Українська',      'uk',      'ltr', 'ua'],
    'ar' => ['العربية',         'ar',      'rtl', 'sa'],
    'fa' => ['فارسی',           'fa',      'rtl', 'ir'],
    'az' => ['Azərbaycan dili', 'az',      'ltr', 'az'],
    'ka' => ['ქართული',         'ka',      'ltr', 'ge'],
    'ro' => ['Română',          'ro',      'ltr', 'ro'],
    'bg' => ['Български',       'bg',      'ltr', 'bg'],
    'sq' => ['Shqip',           'sq',      'ltr', 'al'],
    'sr' => ['Srpski',          'sr-Latn', 'ltr', 'rs'],
    'bs' => ['Bosanski',        'bs',      'ltr', 'ba'],
    'hr' => ['Hrvatski',        'hr',      'ltr', 'hr'],
    'mk' => ['Македонски',      'mk',      'ltr', 'mk'],
];

// Browser language tags that should resolve to one of ours.
const I18N_ALIASES = [
    'sh'  => 'sr', // Serbo-Croatian
    'cnr' => 'sr', // Montenegrin
    'pes' => 'fa', // Iranian Persian
    'prs' => 'fa', // Dari
];

function i18n_is_supported($code) {
    return is_string($code) && isset(I18N_LANGUAGES[$code]);
}

/** Pick the best supported language from an Accept-Language header value. */
function i18n_from_accept_language($header) {
    if (!is_string($header) || $header === '') return null;
    $candidates = [];
    foreach (explode(',', $header) as $i => $part) {
        $bits = explode(';', trim($part));
        $tag = strtolower(trim($bits[0]));
        if ($tag === '' || $tag === '*') continue;
        $q = 1.0;
        foreach (array_slice($bits, 1) as $param) {
            $param = trim($param);
            if (strncmp($param, 'q=', 2) === 0) $q = (float) substr($param, 2);
        }
        if ($q <= 0) continue;
        $primary = explode('-', $tag)[0];
        $primary = I18N_ALIASES[$primary] ?? $primary;
        if (i18n_is_supported($primary)) {
            // Keep header order as a tie-breaker for equal q values.
            $candidates[] = [$q, -$i, $primary];
        }
    }
    if (!$candidates) return null;
    rsort($candidates);
    return $candidates[0][2];
}

function i18n_detect() {
    $fromQuery = isset($_GET['lang']) ? strtolower((string) $_GET['lang']) : null;
    if (i18n_is_supported($fromQuery)) {
        if (!headers_sent()) {
            setcookie(I18N_COOKIE, $fromQuery, [
                'expires' => time() + 60 * 60 * 24 * 365,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'samesite' => 'Lax',
            ]);
        }
        return $fromQuery;
    }
    $fromCookie = $_COOKIE[I18N_COOKIE] ?? null;
    if (i18n_is_supported($fromCookie)) return $fromCookie;

    return i18n_from_accept_language($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '') ?? I18N_DEFAULT;
}

function i18n_load($code) {
    global $I18N_STRINGS, $I18N_FALLBACK;
    $I18N_FALLBACK = require __DIR__ . '/../lang/' . I18N_DEFAULT . '.php';
    $I18N_STRINGS = $code === I18N_DEFAULT ? $I18N_FALLBACK : require __DIR__ . '/../lang/' . $code . '.php';
}

/** Raw translated string (not HTML-escaped), for JavaScript or email. */
function t_raw($key) {
    global $I18N_STRINGS, $I18N_FALLBACK;
    return $I18N_STRINGS[$key] ?? $I18N_FALLBACK[$key] ?? $key;
}

/**
 * Translated string, HTML-escaped. Values in $vars replace {name}
 * placeholders after escaping and are inserted as-is, so they may carry
 * markup (e.g. a phone link) but must be escaped by the caller.
 */
function t($key, array $vars = []) {
    $s = htmlspecialchars(t_raw($key), ENT_QUOTES, 'UTF-8');
    if ($vars) {
        $repl = [];
        foreach ($vars as $k => $v) $repl['{' . $k . '}'] = $v;
        $s = strtr($s, $repl);
    }
    return $s;
}

/** Current URL with ?lang= set, keeping other query parameters (e.g. UTM tags). */
function i18n_url($code, $absolute = false) {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $query = $_GET;
    // slug is added internally by the rewrite rule for clean URLs like /wien
    if (basename($path) !== 'index.php') unset($query['slug']);
    if ($code === null) unset($query['lang']); else $query['lang'] = $code;
    $url = $path . ($query ? '?' . http_build_query($query) : '');
    if ($absolute) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $url = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $url;
    }
    return $url;
}

$lang = i18n_detect();
i18n_load($lang);
[$langName, $pageLang, $pageDir] = I18N_LANGUAGES[$lang];

// The same URL can render different languages, so caches must key on these.
if (!headers_sent()) header('Vary: Accept-Language, Cookie');
