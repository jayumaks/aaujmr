<?php
// header.php — Document opener + navigation partial
// Each page must define $page_title and optionally $page_css (string or array)
// before including this file.

require_once __DIR__ . '/../config/config.php';

// Cache-busting: appends the file's modified time so browsers/CDNs fetch fresh CSS after every update
if (!function_exists('asset_url')) {
    function asset_url(string $path): string {
        $file = __DIR__ . '/../../' . ltrim($path, '/');
        return SITE_URL . '/' . ltrim($path, '/') . (is_file($file) ? '?v=' . filemtime($file) : '');
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Active nav detection
$_request_path = strtok(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '?') ?: '/';
if (SITE_URL !== '' && ($_request_path === SITE_URL || str_starts_with($_request_path, SITE_URL . '/'))) {
    $_request_path = substr($_request_path, strlen(SITE_URL)) ?: '/';
}
$_navpath = $_request_path;

// Auto-noindex admin/utility pages
if (empty($noindex)) {
    foreach (['/admin', '/edit-article', '/upload-article', '/admin-settings'] as $_ap) {
        if (str_starts_with($_navpath, $_ap)) { $noindex = true; break; }
    }
}

// Fetch the most recent volume for nav
try {
    $stmt = db()->query("SELECT volume, year FROM manuscripts ORDER BY year DESC, volume DESC LIMIT 1");
    $latest = $stmt->fetch() ?: [];
} catch (PDOException $e) {
    $latest = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $_page_desc = isset($page_description)
        ? $page_description
        : 'AMBROSE ALLI UNIVERSITY JOURNAL OF MEDICAL RESEARCH (AAUJMR) — peer-reviewed journal of the Faculty of Basic Medical Sciences, Ambrose Alli University, Ekpoma, Nigeria.';
    $__canonical = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
    ?>
    <meta name="description" content="<?= e($_page_desc) ?>">
    <?php if (!empty($noindex)): ?>
    <meta name="robots" content="noindex,nofollow">
    <?php endif; ?>
    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="<?= e(SITE_NAME) ?>">
    <meta property="og:title"       content="<?= isset($page_title) ? e($page_title) . ' — ' . e(SITE_NAME) : e(SITE_NAME) ?>">
    <meta property="og:description" content="<?= e($_page_desc) ?>">
    <meta property="og:url"         content="<?= e($__canonical) ?>">
    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="<?= isset($page_title) ? e($page_title) . ' — ' . e(SITE_NAME) : e(SITE_NAME) ?>">
    <meta name="twitter:description" content="<?= e($_page_desc) ?>">
    <!-- Canonical -->
    <link rel="canonical" href="<?= e($__canonical) ?>">
    <title><?= isset($page_title) ? e($page_title) . ' — ' . SITE_NAME : SITE_NAME ?></title>

    <link rel="icon" href="<?= SITE_URL ?>/favicon/favicon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Newsreader:ital,opsz,wght@0,6..72,400..600;1,6..72,400..600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= e(asset_url('assets/css/index.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset_url('assets/css/header.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset_url('assets/css/footer.css')) ?>">
    <?php foreach ((array)($page_css ?? []) as $css): ?>
        <link rel="stylesheet" href="<?= e(asset_url('assets/css/' . $css)) ?>">
    <?php endforeach; ?>
</head>
<body>

<header class="site-header" id="site-header">
  <div class="header-collapse"><div class="header-collapse-inner">
    <div class="header-top">
        <div class="container">
            <span class="header-faculty">
                <i class="fas fa-university"></i>
                Faculty of Basic Medical Sciences &bull; Ambrose Alli University, Ekpoma
            </span>
            <?php if (!empty($_SESSION['logged_in'])): ?>
            <span class="header-admin-bar">
                <a href="<?= SITE_URL ?>/admin"><i class="fas fa-gauge-high"></i> Dashboard</a>
                <a href="<?= SITE_URL ?>/logout"><i class="fas fa-right-from-bracket"></i> Logout</a>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="header-brand">
        <div class="container">
            <a href="<?= SITE_URL ?>/" class="brand-link">
                <span class="brand-abbr"></span>
                <div class="brand-info">
                    <span class="brand-title">Ambrose Alli University Journal of Medical Research</span>
                    <?php if (SITE_ISSN): ?>
                        <span class="brand-issn">ISSN: <?= e(SITE_ISSN) ?></span>
                    <?php endif; ?>
                </div>
            </a>
        </div>
    </div>
  </div></div>

    <nav class="main-nav" aria-label="Main navigation">
        <div class="container">
            <a href="<?= SITE_URL ?>/" class="nav-mini-brand" aria-label="AAUJMR home">AAUJMR</a>
            <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <ul class="nav-list" id="nav-list">
                <li><a href="<?= SITE_URL ?>/" <?= ($_navpath === '/') ? 'class="active"' : '' ?>><i class="fas fa-home"></i> Home</a></li>
                <li><a href="<?= SITE_URL ?>/current-issue" <?= $_navpath === '/current-issue' ? 'class="active"' : '' ?>><i class="fas fa-newspaper"></i> Current Issue</a></li>
                <li><a href="<?= SITE_URL ?>/archive" <?= $_navpath === '/archive' ? 'class="active"' : '' ?>><i class="fas fa-archive"></i> Archives</a></li>
                <li class="has-dropdown <?= in_array($_navpath, ['/aims-scope','/board','/guide','/ethics-policies'], true) ? 'active' : '' ?>">
                    <button class="dropdown-trigger" aria-expanded="false" aria-haspopup="true">
                        <i class="fas fa-info-circle"></i> About <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-panel" role="menu">
                        <li><a href="<?= SITE_URL ?>/aims-scope" role="menuitem" <?= $_navpath === '/aims-scope' ? 'class="active"' : '' ?>><i class="fas fa-bullseye"></i> Aims &amp; Scope</a></li>
                        <li><a href="<?= SITE_URL ?>/board" role="menuitem" <?= $_navpath === '/board' ? 'class="active"' : '' ?>><i class="fas fa-users"></i> Editorial Board</a></li>
                        <li><a href="<?= SITE_URL ?>/guide" role="menuitem" <?= $_navpath === '/guide' ? 'class="active"' : '' ?>><i class="fas fa-book-open"></i> Author Guidelines</a></li>
                        <li><a href="<?= SITE_URL ?>/ethics-policies" role="menuitem" <?= $_navpath === '/ethics-policies' ? 'class="active"' : '' ?>><i class="fas fa-scale-balanced"></i> Ethics &amp; Policies</a></li>
                    </ul>
                </li>
                <li><a href="<?= SITE_URL ?>/publication-fees" <?= $_navpath === '/publication-fees' ? 'class="active"' : '' ?>><i class="fas fa-file-invoice-dollar"></i> Subscription &amp; Advert Information</a></li>
                <li><a href="<?= SITE_URL ?>/contact" <?= $_navpath === '/contact' ? 'class="active"' : '' ?>><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>

            <a href="<?= SITE_URL ?>/submit" class="nav-submit-btn">
                <i class="fas fa-paper-plane"></i> Submit
            </a>
        </div>
    </nav>
</header>

<main>

<script>
(function () {
    const toggle = document.querySelector('.nav-toggle');
    const navList = document.getElementById('nav-list');
    if (toggle && navList) {
        toggle.addEventListener('click', function () {
            const open = navList.classList.toggle('open');
            toggle.classList.toggle('active', open);
            toggle.setAttribute('aria-expanded', open);
        });
    }
    document.querySelectorAll('.has-dropdown').forEach(function (item) {
        const btn = item.querySelector('.dropdown-trigger');
        if (!btn) return;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const open = item.classList.toggle('open');
            btn.setAttribute('aria-expanded', open);
        });
    });
    // Fold the faculty strip + brand row once the page is scrolled
    const hdr = document.getElementById('site-header');
    if (hdr) {
        let ticking = false;
        const update = function () {
            const y = window.scrollY;
            const roomToScroll = document.documentElement.scrollHeight - window.innerHeight > 500;
            if (!hdr.classList.contains('is-scrolled') && y > 90 && roomToScroll) hdr.classList.add('is-scrolled');
            else if (hdr.classList.contains('is-scrolled') && y < 8) hdr.classList.remove('is-scrolled');
            // Safety net: if the host's CSS/markup stops position:sticky from working,
            // pin the header with fixed positioning and keep its place with a spacer.
            if (y > 200 && !hdr.classList.contains('is-pinned') && hdr.getBoundingClientRect().top < -1) {
                const spacer = document.createElement('div');
                spacer.style.height = hdr.offsetHeight + 'px';
                hdr.parentNode.insertBefore(spacer, hdr);
                hdr.classList.add('is-pinned');
            }
            ticking = false;
        };
        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; requestAnimationFrame(update); }
        }, { passive: true });
        update();
    }
    document.addEventListener('click', function () {
        document.querySelectorAll('.has-dropdown.open').forEach(function (item) {
            item.classList.remove('open');
            const btn = item.querySelector('.dropdown-trigger');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    });
})();
</script>
