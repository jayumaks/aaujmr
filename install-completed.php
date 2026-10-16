<?php
declare(strict_types=1);

/**
 * AAUJMR one-click installer.
 *
 * Local XAMPP:
 *   Server: localhost
 *   MySQL user: root
 *   Password: (blank by default)
 *   Database: aaujmr
 *
 * cPanel:
 *   Create the database/user in cPanel first, then enter those credentials here.
 */
session_start();

$root = __DIR__;
$configFile = $root . '/src/config/config.php';
$schemaFile = $root . '/database/aaujmr.sql';
$seedFile   = $root . '/database/seed-data.sql';

$message = '';
$error = '';
$done = false;

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

function execute_sql_file(PDO $pdo, string $file): void
{
    $sql = file_get_contents($file);
    if ($sql === false) throw new RuntimeException('Unable to read SQL file: ' . basename($file));

    // Remove MySQL dump comments/directives that are safe but can confuse simple splitters.
    $sql = preg_replace('/\/\*!\d+.*?\*\//s', '', $sql) ?? $sql;
    $sql = preg_replace('/--[^\r\n]*(?:\r?\n|$)/', "\n", $sql) ?? $sql;
    $sql = trim($sql);

    // Split statements while respecting quoted strings.
    $statements = [];
    $buf = '';
    $quote = null;
    $len = strlen($sql);
    for ($i = 0; $i < $len; $i++) {
        $ch = $sql[$i];
        $next = $i + 1 < $len ? $sql[$i + 1] : '';
        if ($quote !== null) {
            $buf .= $ch;
            if ($ch === '\\' && $next !== '') {
                $buf .= $next;
                $i++;
                continue;
            }
            if ($ch === $quote) {
                $quote = null;
            }
        } else {
            if ($ch === "'" || $ch === '"' || $ch === '`') {
                $quote = $ch;
                $buf .= $ch;
            } elseif ($ch === ';') {
                if (trim($buf) !== '') $statements[] = trim($buf);
                $buf = '';
            } else {
                $buf .= $ch;
            }
        }
    }
    if (trim($buf) !== '') $statements[] = trim($buf);

    foreach ($statements as $statement) {
        if ($statement !== '') $pdo->exec($statement);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['db_host'] ?? 'localhost');
    $user = trim($_POST['db_user'] ?? 'root');
    $pass = (string)($_POST['db_pass'] ?? '');
    $name = trim($_POST['db_name'] ?? 'aaujmr');
    $siteUrl = trim($_POST['site_url'] ?? '/aaujmr');
    $adminUser = trim($_POST['admin_user'] ?? 'admin');
    $adminPass = (string)($_POST['admin_pass'] ?? '');
    $reset = isset($_POST['reset_database']);

    if ($siteUrl === '/') $siteUrl = '';
    $siteUrl = '/' . trim($siteUrl, '/');
    if ($siteUrl === '/') $siteUrl = '';

    if ($host === '' || $user === '' || $name === '') {
        $error = 'Database host, username and database name are required.';
    } elseif (!preg_match('/^[A-Za-z0-9_$-]+$/', $name)) {
        $error = 'Database name contains unsupported characters.';
    } elseif ($adminUser === '' || strlen($adminPass) < 8) {
        $error = 'Admin username is required and the admin password must be at least 8 characters.';
    } elseif (!is_file($schemaFile) || !is_file($seedFile)) {
        $error = 'Installation SQL files are missing from the package.';
    } else {
        try {
            $serverPdo = new PDO('mysql:host=' . $host . ';charset=utf8mb4', $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            // CREATE DATABASE is normally available to XAMPP root. On cPanel,
            // users should create the database in cPanel and provide its name.
            $serverPdo->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '``', $name) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

            $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8mb4', $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            if ($reset) {
                $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
                if ($tables) {
                    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
                    foreach ($tables as $table) {
                        $pdo->exec('DROP TABLE IF EXISTS `' . str_replace('`', '``', $table) . '`');
                    }
                    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
                }
            }

            execute_sql_file($pdo, $schemaFile);
            execute_sql_file($pdo, $seedFile);

            $hash = password_hash($adminPass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO user_accounts (username,password,role) VALUES (?,?,\'admin\') ON DUPLICATE KEY UPDATE password=VALUES(password), role=\'admin\'');
            $stmt->execute([$adminUser, $hash]);

            // Write the production/local configuration automatically.
            $config = <<<'CFG'
<?php
declare(strict_types=1);

define('DB_HOST', '__DB_HOST__');
define('DB_USER', '__DB_USER__');
define('DB_PASS', '__DB_PASS__');
define('DB_NAME', '__DB_NAME__');

define('SITE_NAME', 'Ambrose Alli University Journal of Medical Research');
define('SITE_ABBR', 'AAUJMR');
define('SITE_ISSN', '');
define('SITE_URL', '__SITE_URL__');

const CATEGORIES = [
    'Basic Medical Sciences',
    'Clinical Medicine',
    'Life & Biological Sciences',
    'Environmental Sciences',
    'Natural Sciences',
    'Physical Sciences',
    'Chemical Sciences',
];

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('Database connection failed. Please check src/config/config.php.');
        }
    }
    return $pdo;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}
function csrf_verify(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}
function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try { foreach (db()->query('SELECT `key`,`value` FROM site_settings') as $row) $cache[$row['key']] = $row['value']; }
        catch (PDOException) {}
    }
    return $cache[$key] ?? $default;
}
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $path): never { header('Location: ' . SITE_URL . $path); exit; }
function require_admin(): void
{
    if (empty($_SESSION['logged_in']) || ($_SESSION['role'] ?? '') !== 'admin') redirect('/login');
}
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Strict',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
]);
CFG;
            $replacements = [
                '__DB_HOST__' => addslashes($host),
                '__DB_USER__' => addslashes($user),
                '__DB_PASS__' => addslashes($pass),
                '__DB_NAME__' => addslashes($name),
                '__SITE_URL__' => addslashes($siteUrl),
            ];
            $config = str_replace(array_keys($replacements), array_values($replacements), $config);
            if (file_put_contents($configFile, $config, LOCK_EX) === false) {
                throw new RuntimeException('Database was installed, but config.php could not be written. Please update it manually.');
            }

            // Protect installer after successful setup.
            @rename($root . '/install.php', $root . '/install-completed.php');
            $done = true;
            $message = 'AAUJMR has been installed successfully.';
        } catch (Throwable $e) {
            $error = 'Installation failed: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>AAUJMR Installation</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f6f8;margin:0;color:#17202a}.wrap{max-width:760px;margin:40px auto;padding:0 18px}.card{background:#fff;border:1px solid #ddd;border-radius:12px;padding:28px;box-shadow:0 8px 30px #00000010}h1{margin-top:0;color:#123d63}.grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}@media(max-width:650px){.grid{grid-template-columns:1fr}}label{display:block;font-weight:600;margin-bottom:6px}input{width:100%;box-sizing:border-box;padding:11px;border:1px solid #bbb;border-radius:7px;margin-bottom:15px}button,.btn{display:inline-block;background:#123d63;color:#fff;border:0;border-radius:7px;padding:12px 18px;text-decoration:none;cursor:pointer;font-weight:700}.note{background:#eef5fb;padding:14px;border-radius:8px;margin:15px 0}.error{background:#ffe9e9;color:#9d1c1c;padding:14px;border-radius:8px}.ok{background:#e9f8ed;color:#176b2c;padding:14px;border-radius:8px}.danger{color:#9d1c1c}.small{font-size:.9rem;color:#555}.check{display:flex;gap:8px;align-items:flex-start}.check input{width:auto;margin:3px 0}.check label{font-weight:400}
</style>
</head>
<body><div class="wrap"><div class="card">
<h1>AAUJMR Installation</h1>
<p>Ambrose Alli University Journal of Medical Research (AAUJMR)</p>
<?php if ($error): ?><div class="error"><?=h($error)?></div><?php endif; ?>
<?php if ($done): ?>
<div class="ok"><strong><?=h($message)?></strong><br><br>Database, sample journal content and editorial-board data have been loaded.</div>
<p><a class="btn" href="<?=h($siteUrl ?: '')?>/">Open AAUJMR Website</a></p>
<p><a class="btn" href="<?=h(($siteUrl ?: ''))?>/login">Open Admin Login</a></p>
<p class="small">For security, the installer has been renamed to <code>install-completed.php</code>. You may delete that file.</p>
<?php else: ?>
<div class="note"><strong>XAMPP:</strong> Start Apache and MySQL first. The default values below are ready for a standard XAMPP installation.</div>
<form method="post">
<h2>Database</h2><div class="grid">
<div><label>MySQL Host</label><input name="db_host" value="<?=h($_POST['db_host'] ?? 'localhost')?>" required></div>
<div><label>Database Name</label><input name="db_name" value="<?=h($_POST['db_name'] ?? 'aaujmr')?>" required></div>
<div><label>Database User</label><input name="db_user" value="<?=h($_POST['db_user'] ?? 'root')?>" required></div>
<div><label>Database Password</label><input type="password" name="db_pass" value="<?=h($_POST['db_pass'] ?? '')?>"></div>
</div>
<h2>Website</h2><label>Site URL Path</label><input name="site_url" value="<?=h($_POST['site_url'] ?? '/aaujmr')?>"><p class="small">Use <code>/aaujmr</code> for <code>http://localhost/aaujmr</code>. Use an empty value for a domain/subdomain document root.</p>
<h2>Admin Account</h2><div class="grid">
<div><label>Admin Username</label><input name="admin_user" value="<?=h($_POST['admin_user'] ?? 'admin')?>" required></div>
<div><label>Admin Password</label><input type="password" name="admin_pass" required minlength="8"><div class="small">Minimum 8 characters.</div></div>
</div>
<p class="check"><input type="checkbox" id="reset" name="reset_database"><label for="reset">Reset existing database before installation <span class="danger">(deletes existing AAUJMR tables)</span></label></p>
<br><button type="submit">Install AAUJMR</button>
</form>
<?php endif; ?>
</div></div></body></html>
