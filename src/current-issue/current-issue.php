<?php
$page_title = 'Current Issue';
$page_css   = ['articles.css'];
require_once __DIR__ . '/../layout/header.php';

// Get latest volume/year
$latest = db()->query("
    SELECT volume, year FROM manuscripts ORDER BY year DESC, volume DESC LIMIT 1
")->fetch();

if (!$latest) {
    $volume = 0; $year = 0;
} else {
    $volume = (int)$latest['volume'];
    $year   = (int)$latest['year'];
}

if ($volume > 0) {
    // Reuse articles page logic
    $_GET['volume'] = $volume;
    $_GET['year']   = $year;
    include __DIR__ . '/../articles/articles.php';
    exit;
}
?>

<div class="page-banner">
    <div class="container">
        <h1>Current Issue</h1>
        <p class="subtitle">No issues published yet.</p>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <p class="text-muted">The current issue will appear here once articles are published.</p>
        <a href="<?= SITE_URL ?>/archive" class="btn btn-primary mt-md">
            <i class="fas fa-archive"></i> Browse Archives
        </a>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
