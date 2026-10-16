<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (empty($username) || empty($password)) {
            $error = 'Please fill in all fields.';
        } else {
            $stmt = db()->prepare('SELECT * FROM user_accounts WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['role']      = $user['role'];
                redirect($user['role'] === 'admin' ? '/admin' : '/');
            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
}
$page_title = 'Login';
$page_css   = ['login.css'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Login -- <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/index.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/login.css">
</head>
<body class="login-body">
<div class="login-wrapper">
    <div class="login-brand">
        <span class="login-abbr">AAUJMR</span>
        <span class="login-journal-name">Ambrose Alli University Journal of Medical Research</span>
        <span class="login-faculty">Admin Portal</span>
    </div>
    <div class="login-card">
        <h1>Sign In</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="username" class="required">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                       autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="password" class="required">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                       autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-primary login-submit">
                <i class="fas fa-right-to-bracket"></i> Sign In
            </button>
        </form>
    </div>
    <a href="<?= SITE_URL ?>/" class="login-back">
        <i class="fas fa-arrow-left"></i> Back to Journal
    </a>
</div>
</body>
</html>