<?php
declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_USER', 'aauekpo5_aaujmruser');
define('DB_PASS', 'DEW}@@h-!jL3_ig(');
define('DB_NAME', 'aauekpo5_aaujmr');

define('SITE_NAME', 'Ambrose Alli University Journal of Medical Research');
define('SITE_ABBR', 'AAUJMR');
define('SITE_ISSN', '');
define('SITE_URL', '');

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