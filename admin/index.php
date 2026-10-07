<?php
/*
 * Lead panel: lists partnership applications with filters and CSV export.
 * Turned on by api/admin_config.php (not in git; see admin_config.example.php).
 */
declare(strict_types=1);

require_once __DIR__ . '/../includes/form_guard.php';

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');

$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_name('asg_admin');
session_set_cookie_params(['lifetime' => 0, 'path' => '/admin/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Strict']);
session_start();

function h($v): string {
    // DB values are stored HTML-escaped; decode first so nothing is escaped twice.
    return htmlspecialchars(html_entity_decode((string) $v, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
}
function csrf(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_ok(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
}

$configFile = __DIR__ . '/../api/admin_config.php';
$config = is_file($configFile) ? require $configFile : [];
$users = is_array($config['users'] ?? null) ? $config['users'] : [];
$allowedIps = is_array($config['allowed_ips'] ?? null) ? $config['allowed_ips'] : [];
$ip = fg_client_ip();

if ($allowedIps && !in_array($ip, $allowedIps, true)) {
    http_response_code(403);
    exit('Access denied.');
}

$error = '';
$view = 'login';

// --- Not configured yet: offer a hash generator (it stores nothing) ---
if (!$users) {
    $view = 'setup';
    $generated = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok()) {
        $u = preg_replace('/[^a-z0-9._-]/i', '', (string) ($_POST['username'] ?? ''));
        $p = (string) ($_POST['password'] ?? '');
        if (!fg_rate_limit('admin-setup:' . $ip, [600 => 20])) {
            $error = 'Çok fazla deneme. Biraz sonra tekrar deneyin.';
        } elseif ($u === '' || strlen($p) < 12) {
            $error = 'Kullanıcı adı gerekli, şifre en az 12 karakter olmalı.';
        } else {
            $generated = "'" . $u . "' => '" . password_hash($p, PASSWORD_DEFAULT) . "',";
        }
    }
}

// --- Login / logout ---
elseif (isset($_POST['action']) && $_POST['action'] === 'logout' && csrf_ok()) {
    $_SESSION = [];
    session_destroy();
    header('Location: /admin/');
    exit;
} elseif (empty($_SESSION['admin_user']) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $bucket = 'admin-login:' . $ip;
    if (!csrf_ok()) {
        $error = 'Oturum süresi doldu, tekrar deneyin.';
    } elseif (!fg_rate_limit($bucket, [900 => 5], false)) {
        $error = 'Çok fazla hatalı deneme. 15 dakika sonra tekrar deneyin.';
    } else {
        $u = (string) ($_POST['username'] ?? '');
        $p = (string) ($_POST['password'] ?? '');
        $hash = $users[$u] ?? null;
        if (is_string($hash) && password_verify($p, $hash)) {
            session_regenerate_id(true);
            $_SESSION['admin_user'] = $u;
            $_SESSION['seen'] = time();
            header('Location: /admin/');
            exit;
        }
        fg_rate_hit($bucket, 900);
        $error = 'Kullanıcı adı veya şifre hatalı.';
    }
}

// Signed in? (2 hours idle timeout)
if ($view !== 'setup' && !empty($_SESSION['admin_user'])) {
    if (time() - (int) ($_SESSION['seen'] ?? 0) > 7200 || !isset($users[$_SESSION['admin_user']])) {
        $_SESSION = [];
        session_destroy();
    } else {
        $_SESSION['seen'] = time();
        $view = 'list';
    }
}

// --- Lead list ---
$rows = [];
$total = 0;
$perPage = 50;
$page = max(1, (int) ($_GET['p'] ?? 1));
$f = [
    'from'   => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($_GET['from'] ?? '')) ? $_GET['from'] : '',
    'to'     => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($_GET['to'] ?? '')) ? $_GET['to'] : '',
    'office' => mb_substr((string) ($_GET['office'] ?? ''), 0, 100),
    'lang'   => mb_substr((string) ($_GET['lang'] ?? ''), 0, 10),
    'role'   => mb_substr((string) ($_GET['role'] ?? ''), 0, 100),
    'q'      => mb_substr(trim((string) ($_GET['q'] ?? '')), 0, 100),
];
$offices = [];
$roles = [];
$langs = [];

if ($view === 'list') {
    $officesJson = json_decode((string) @file_get_contents(__DIR__ . '/../api/offices.json'), true);
    foreach (($officesJson['data'] ?? []) as $o) $offices[$o['slug']] = $o['display_name'] . ' (' . $o['country'] . ')';
    asort($offices);

    require __DIR__ . '/../api/db_connect.php';
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error = 'Veritabanına bağlanılamadı.';
    } else {
        $conn->set_charset('utf8mb4');
        $cols = [];
        if ($res = $conn->query('SHOW COLUMNS FROM partnership_applications')) {
            while ($c = $res->fetch_assoc()) $cols[$c['Field']] = true;
        }
        $hasNew = isset($cols['office'], $cols['lang']);

        $where = [];
        $params = [];
        if ($f['from'] !== '') { $where[] = 'created_at >= ?'; $params[] = $f['from'] . ' 00:00:00'; }
        if ($f['to'] !== '')   { $where[] = 'created_at <= ?'; $params[] = $f['to'] . ' 23:59:59'; }
        if ($f['role'] !== '') { $where[] = 'role = ?'; $params[] = htmlspecialchars($f['role'], ENT_QUOTES, 'UTF-8'); }
        if ($hasNew && $f['office'] !== '') {
            if ($f['office'] === '_global') { $where[] = "(office = '' OR office IS NULL)"; }
            else { $where[] = 'office = ?'; $params[] = $f['office']; }
        }
        if ($hasNew && $f['lang'] !== '') { $where[] = 'lang = ?'; $params[] = $f['lang']; }
        if ($f['q'] !== '') {
            $where[] = '(name LIKE ? OR email LIKE ? OR phone LIKE ?)';
            $like = '%' . addcslashes(htmlspecialchars($f['q'], ENT_QUOTES, 'UTF-8'), '%_\\') . '%';
            array_push($params, $like, $like, $like);
        }
        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

        $run = function (string $sql, array $params) use ($conn) {
            $stmt = $conn->prepare($sql);
            if (!$stmt) return null;
            if ($params) $stmt->bind_param(str_repeat('s', count($params)), ...$params);
            $stmt->execute();
            return $stmt->get_result();
        };

        if ($r = $conn->query('SELECT DISTINCT role FROM partnership_applications ORDER BY role')) {
            while ($x = $r->fetch_row()) $roles[] = $x[0];
        }
        if ($hasNew && ($r = $conn->query("SELECT DISTINCT lang FROM partnership_applications WHERE lang <> '' ORDER BY lang"))) {
            while ($x = $r->fetch_row()) $langs[] = $x[0];
        }

        $exportCsv = isset($_GET['export']);
        if ($res = $run('SELECT COUNT(*) FROM partnership_applications' . $whereSql, $params)) $total = (int) $res->fetch_row()[0];
        $sql = 'SELECT * FROM partnership_applications' . $whereSql . ' ORDER BY created_at DESC, id DESC';
        if (!$exportCsv) $sql .= ' LIMIT ' . $perPage . ' OFFSET ' . (($page - 1) * $perPage);
        if ($res = $run($sql, $params)) $rows = $res->fetch_all(MYSQLI_ASSOC);
        $conn->close();

        if ($exportCsv) {
            $headers = ['id', 'created_at', 'role', 'name', 'email', 'phone', 'volume', 'message', 'office', 'lang', 'source',
                        'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'referrer', 'url'];
            $headers = array_values(array_filter($headers, function ($c) use ($cols) { return isset($cols[$c]); }));
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="basvurular-' . date('Y-m-d') . '.csv"');
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // Excel'in UTF-8'i tanıması için
            fputcsv($out, $headers, ';', '"', '');
            foreach ($rows as $row) {
                $line = [];
                foreach ($headers as $c) {
                    $v = html_entity_decode((string) ($row[$c] ?? ''), ENT_QUOTES, 'UTF-8');
                    // Excel formül enjeksiyonunu engelle
                    // (telefon numaraları gibi sadece rakam içeren değerler hariç)
                    if ($v !== '' && strpos('=+-@', $v[0]) !== false && !preg_match('/^[+\-]?[\d\s().\/-]+$/', $v)) $v = "'" . $v;
                    $line[] = $v;
                }
                fputcsv($out, $line, ';', '"', '');
            }
            fclose($out);
            exit;
        }
    }
}

$pages = max(1, (int) ceil($total / $perPage));
function qs(array $f, array $extra = []): string {
    return '?' . http_build_query(array_filter(array_merge($f, $extra), function ($v) { return $v !== '' && $v !== null; }));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="robots" content="noindex, nofollow" />
<title>Başvurular · Acıbadem Partner Portal</title>
<style>
  :root{--navy:#092c74;--teal:#11a39a;--ink:#0f1b3d;--muted:#5b6478;--line:#e3e9f2;--soft:#f4f7fb;--err:#c0392b}
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;color:var(--ink);background:var(--soft);font-size:14px}
  header{background:var(--navy);color:#fff;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;gap:12px}
  header h1{font-size:16px;margin:0}
  main{padding:20px;max-width:1500px;margin:0 auto}
  .card{background:#fff;border:1px solid var(--line);border-radius:12px;padding:20px}
  .login{max-width:380px;margin:60px auto}
  label{display:block;font-size:12px;color:var(--muted);margin-bottom:4px}
  input,select{width:100%;padding:8px 10px;border:1px solid var(--line);border-radius:8px;font:inherit;background:#fff}
  button,.btn{display:inline-block;padding:8px 14px;border:0;border-radius:8px;background:var(--teal);color:#fff;font:inherit;font-weight:600;cursor:pointer;text-decoration:none;white-space:nowrap}
  .btn-ghost{background:transparent;border:1px solid rgba(255,255,255,.5)}
  .btn-light{background:#fff;color:var(--navy);border:1px solid var(--line)}
  .stack > * + *{margin-top:12px}
  .err{color:var(--err);font-weight:600}
  .filters{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;align-items:end;margin-bottom:16px}
  .meta{display:flex;justify-content:space-between;align-items:center;margin:0 0 10px;color:var(--muted);flex-wrap:wrap;gap:8px}
  .tbl{overflow-x:auto;background:#fff;border:1px solid var(--line);border-radius:12px}
  table{border-collapse:collapse;width:100%}
  th,td{text-align:left;padding:9px 12px;border-bottom:1px solid var(--line);vertical-align:top}
  th{background:var(--soft);font-size:12px;color:var(--muted);white-space:nowrap}
  td.nowrap{white-space:nowrap}
  td .msg{max-width:340px;white-space:pre-wrap;color:var(--muted)}
  .pager{display:flex;gap:8px;justify-content:center;margin-top:14px}
  code{display:block;word-break:break-all;background:var(--soft);padding:10px;border-radius:8px;user-select:all}
</style>
</head>
<body>
<header>
  <h1>Acıbadem Partner Portal · Başvurular</h1>
  <?php if ($view === 'list'): ?>
  <form method="post"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>" /><input type="hidden" name="action" value="logout" />
    <button class="btn-ghost" type="submit"><?= h($_SESSION['admin_user']) ?> · Çıkış</button></form>
  <?php endif; ?>
</header>
<main>
<?php if ($view === 'setup'): ?>
  <div class="card login stack">
    <h2 style="margin:0">Panel henüz açılmadı</h2>
    <p>Sunucuda <b>api/admin_config.php</b> dosyası yok ya da içinde kullanıcı yok. Aşağıda bir kullanıcı adı ve şifre girin; çıkan satırı o dosyadaki <b>'users'</b> listesine yapıştırın. Bu form hiçbir şey kaydetmez.</p>
    <?php if ($error): ?><p class="err"><?= h($error) ?></p><?php endif; ?>
    <?php if (!empty($generated)): ?><p>Bu satırı kopyalayın:</p><code><?= h($generated) ?></code><?php endif; ?>
    <form method="post" class="stack" autocomplete="off">
      <input type="hidden" name="csrf" value="<?= h(csrf()) ?>" />
      <div><label for="u">Kullanıcı adı</label><input id="u" name="username" required /></div>
      <div><label for="p">Şifre (en az 12 karakter)</label><input id="p" name="password" type="password" minlength="12" required /></div>
      <button type="submit">Satırı oluştur</button>
    </form>
  </div>
<?php elseif ($view === 'login'): ?>
  <form method="post" class="card login stack">
    <h2 style="margin:0">Giriş</h2>
    <?php if ($error): ?><p class="err"><?= h($error) ?></p><?php endif; ?>
    <input type="hidden" name="csrf" value="<?= h(csrf()) ?>" />
    <input type="hidden" name="action" value="login" />
    <div><label for="u">Kullanıcı adı</label><input id="u" name="username" autocomplete="username" required autofocus /></div>
    <div><label for="p">Şifre</label><input id="p" name="password" type="password" autocomplete="current-password" required /></div>
    <button type="submit">Giriş yap</button>
  </form>
<?php else: ?>
  <form method="get" class="card filters">
    <div><label for="from">Başlangıç</label><input id="from" type="date" name="from" value="<?= h($f['from']) ?>" /></div>
    <div><label for="to">Bitiş</label><input id="to" type="date" name="to" value="<?= h($f['to']) ?>" /></div>
    <div><label for="office">Ofis</label><select id="office" name="office">
      <option value="">Tümü</option>
      <option value="_global"<?= $f['office'] === '_global' ? ' selected' : '' ?>>Global (HQ sayfası)</option>
      <?php foreach ($offices as $slug => $label): ?><option value="<?= h($slug) ?>"<?= $f['office'] === $slug ? ' selected' : '' ?>><?= h($label) ?></option><?php endforeach; ?>
    </select></div>
    <div><label for="lang">Dil</label><select id="lang" name="lang"><option value="">Tümü</option>
      <?php foreach ($langs as $l): ?><option<?= $f['lang'] === $l ? ' selected' : '' ?>><?= h($l) ?></option><?php endforeach; ?>
    </select></div>
    <div><label for="role">Rol</label><select id="role" name="role"><option value="">Tümü</option>
      <?php foreach ($roles as $r): $rv = html_entity_decode((string) $r, ENT_QUOTES, 'UTF-8'); ?><option value="<?= h($rv) ?>"<?= $f['role'] === $rv ? ' selected' : '' ?>><?= h($rv) ?></option><?php endforeach; ?>
    </select></div>
    <div><label for="q">Ara (ad, e-posta, telefon)</label><input id="q" name="q" value="<?= h($f['q']) ?>" /></div>
    <div style="display:flex;gap:8px"><button type="submit">Filtrele</button><a class="btn btn-light" href="/admin/">Temizle</a></div>
  </form>

  <?php if ($error): ?><p class="err"><?= h($error) ?></p><?php endif; ?>
  <div class="meta">
    <span><b><?= $total ?></b> başvuru<?= $pages > 1 ? ' · sayfa ' . $page . ' / ' . $pages : '' ?></span>
    <a class="btn" href="<?= h(qs($f, ['export' => 1])) ?>">CSV indir</a>
  </div>
  <div class="tbl"><table>
    <thead><tr><th>Tarih</th><th>Ad Soyad</th><th>Rol</th><th>E-posta</th><th>Telefon</th><th>Hacim</th><th>Ofis</th><th>Dil</th><th>Kaynak</th><th>Kampanya</th><th>Mesaj</th></tr></thead>
    <tbody>
    <?php if (!$rows): ?><tr><td colspan="11" style="text-align:center;color:var(--muted);padding:30px">Başvuru bulunamadı.</td></tr><?php endif; ?>
    <?php foreach ($rows as $row):
        $off = (string) ($row['office'] ?? '');
        $campaign = trim(implode(' / ', array_filter([$row['utm_source'] ?? '', $row['utm_medium'] ?? '', $row['utm_campaign'] ?? ''])));
    ?>
      <tr>
        <td class="nowrap"><?= h($row['created_at']) ?></td>
        <td><?= h($row['name']) ?></td>
        <td><?= h($row['role']) ?></td>
        <td><a href="mailto:<?= h($row['email']) ?>"><?= h($row['email']) ?></a></td>
        <td class="nowrap"><a href="tel:<?= h(preg_replace('/[^\d+]/', '', html_entity_decode((string) $row['phone']))) ?>"><?= h($row['phone']) ?></a></td>
        <td class="nowrap"><?= h($row['volume']) ?></td>
        <td><?= $off === '' ? 'Global' : h($offices[$off] ?? $off) ?></td>
        <td><?= h($row['lang'] ?? '') ?></td>
        <td><?= h($row['source'] ?? '') ?></td>
        <td><?= h($campaign) ?></td>
        <td><div class="msg"><?= h($row['message']) ?></div></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php if ($pages > 1): ?>
  <div class="pager">
    <?php if ($page > 1): ?><a class="btn btn-light" href="<?= h(qs($f, ['p' => $page - 1])) ?>">‹ Önceki</a><?php endif; ?>
    <?php if ($page < $pages): ?><a class="btn btn-light" href="<?= h(qs($f, ['p' => $page + 1])) ?>">Sonraki ›</a><?php endif; ?>
  </div>
  <?php endif; ?>
<?php endif; ?>
</main>
</body>
</html>
