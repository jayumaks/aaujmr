<?php
$page_title = 'Archives';
$page_css   = ['archive.css'];
require_once __DIR__ . '/../layout/header.php';

$filter_cat = isset($_GET['cat']) && in_array($_GET['cat'], CATEGORIES, true)
    ? $_GET['cat'] : '';

// Fetch all distinct volume+issue+year combos, optionally filtered
if ($filter_cat !== '') {
    $stmt = db()->prepare("
        SELECT DISTINCT volume, issue, year
        FROM manuscripts
        WHERE category = ?
        ORDER BY year DESC, volume DESC
    ");
    $stmt->execute([$filter_cat]);
} else {
    $stmt = db()->query("
        SELECT DISTINCT volume, issue, year
        FROM manuscripts
        ORDER BY year DESC, volume DESC
    ");
}
$issues = $stmt->fetchAll();

// Category issue-counts for sidebar
$cat_stmt = db()->query("
    SELECT category, COUNT(DISTINCT volume, year) AS cnt
    FROM manuscripts
    GROUP BY category
    ORDER BY cnt DESC
");
$cat_stats = $cat_stmt->fetchAll();
?>

<!-- Banner -->
<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-archive"></i> <?= SITE_NAME ?></p>
        <h1>Archives</h1>
        <p class="subtitle">All published volumes and issues</p>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="archive-layout">

            <!-- Category sidebar -->
            <aside class="cat-sidebar">
                <h3 class="sidebar-heading">
                    <i class="fas fa-tag"></i> Browse by Category
                </h3>
                <ul class="cat-list">
                    <li>
                        <a href="<?= SITE_URL ?>/archive"
                           class="cat-link <?= $filter_cat === '' ? 'active' : '' ?>">
                            All Categories
                        </a>
                    </li>
                    <?php foreach ($cat_stats as $row): ?>
                    <li>
                        <a href="<?= SITE_URL ?>/archive?cat=<?= urlencode($row['category']) ?>"
                           class="cat-link <?= $filter_cat === $row['category'] ? 'active' : '' ?>">
                            <?= e($row['category']) ?>
                            <span class="cat-count"><?= $row['cnt'] ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- Issue list -->
            <div class="archive-main">
                <p class="intro-text">
                    Permission is granted to distribute any article in this journal for
                    non-profit, educational purposes, provided it is copied in its entirety
                    and the journal is credited.
                </p>

                <?php if (empty($issues)): ?>
                    <p class="text-muted">No issues found.</p>
                <?php else: ?>
                    <div class="issue-grid">
                        <?php foreach ($issues as $iss): ?>
                        <a href="<?= SITE_URL ?>/articles?volume=<?= $iss['volume'] ?>&year=<?= $iss['year'] ?><?= !empty($iss['issue']) ? '&issue=' . urlencode($iss['issue']) : '' ?><?= $filter_cat ? '&cat=' . urlencode($filter_cat) : '' ?>"
                           class="issue-card">
                            <div class="issue-vol">Vol. <?= e((string)$iss['volume']) ?></div>
                            <?php if (!empty($iss['issue'])): ?>
                                <div class="issue-no">Issue <?= e($iss['issue']) ?></div>
                            <?php endif; ?>
                            <div class="issue-year"><?= e((string)$iss['year']) ?></div>
                            <i class="fas fa-arrow-right issue-arrow"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>