<?php
$page_title = 'Articles';
$page_css   = ['articles.css'];
require_once __DIR__ . '/../layout/header.php';

$volume   = isset($_GET['volume']) ? (int)$_GET['volume'] : 0;
$year     = isset($_GET['year'])   ? (int)$_GET['year']   : 0;
$issue    = isset($_GET['issue'])  ? $_GET['issue']        : '';
$cat      = isset($_GET['cat'])    ? $_GET['cat']          : '';

if ($volume === 0 || $year === 0) {
    redirect('/archive');
}

// Fetch issue metadata
$stmt = db()->prepare("SELECT * FROM manuscripts WHERE volume = ? AND year = ? LIMIT 1");
$stmt->execute([$volume, $year]);
$meta = $stmt->fetch();

if (!$meta) {
    echo '<div class="container" style="padding:3rem 0"><p>No articles found for Volume ' . $volume . ' (' . $year . ').</p></div>';
    include __DIR__ . '/../layout/footer.php'; exit;
}

// Build category filter query
$params = [$volume, $year];
$where  = "volume = ? AND year = ?";
if ($issue !== '' && trim($issue) !== '') {
    $where .= " AND issue = ?";
    $params[] = trim($issue);
}
if ($cat !== '' && in_array($cat, CATEGORIES, true)) {
    $where .= " AND category = ?";
    $params[] = $cat;
}
// Pagination
$per_page = 15;
$page     = max(1, (int)($_GET['p'] ?? 1));

// Count total for pagination
$count_stmt = db()->prepare("SELECT COUNT(*) FROM manuscripts WHERE {$where}");
$count_stmt->execute($params);
$total_articles = (int)$count_stmt->fetchColumn();
$total_pages    = (int)ceil($total_articles / $per_page);
$page           = min($page, max(1, $total_pages));
$offset         = ($page - 1) * $per_page;

$stmt2 = db()->prepare("SELECT * FROM manuscripts WHERE {$where} ORDER BY id ASC LIMIT {$per_page} OFFSET {$offset}");
$stmt2->execute($params);
$articles = $stmt2->fetchAll();

// Category counts for this issue
$count_params = [$volume, $year];
$count_where = "volume = ? AND year = ?";
if ($issue !== '' && trim($issue) !== '') {
    $count_where .= " AND issue = ?";
    $count_params[] = trim($issue);
}
$counts_stmt = db()->prepare("
    SELECT category, COUNT(*) AS cnt
    FROM manuscripts WHERE {$count_where}
    GROUP BY category
");
$counts_stmt->execute($count_params);
$cat_counts = [];
foreach ($counts_stmt->fetchAll() as $row) {
    $cat_counts[$row['category']] = $row['cnt'];
}
$total = array_sum($cat_counts);
?>

<!-- Page banner -->
<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-archive"></i> <?= SITE_ABBR ?> &bull; Volume <?= $volume ?></p>
        <h1>Volume <?= $volume ?>, <?= $year ?></h1>
        <?php if (!empty($meta['issue'])): ?>
            <p class="subtitle">Issue <?= e($meta['issue']) ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="articles-layout">

            <!-- Sidebar: Category filter -->
            <aside class="cat-sidebar">
                <h3 class="sidebar-heading">
                    <i class="fas fa-filter"></i> Filter by Category
                </h3>
                <ul class="cat-list">
                    <li>
                        <a href="?volume=<?= $volume ?>&year=<?= $year ?><?= $issue !== '' ? '&issue=' . urlencode($issue) : '' ?>"
                           class="cat-link <?= $cat === '' ? 'active' : '' ?>">
                            All Articles
                            <span class="cat-count"><?= $total ?></span>
                        </a>
                    </li>
                    <?php foreach (CATEGORIES as $c): ?>
                        <?php if (isset($cat_counts[$c])): ?>
                        <li>
                            <a href="?volume=<?= $volume ?>&year=<?= $year ?>&cat=<?= urlencode($c) ?><?= $issue !== '' ? '&issue=' . urlencode($issue) : '' ?>"
                               class="cat-link <?= $cat === $c ? 'active' : '' ?>">
                                <?= e($c) ?>
                                <span class="cat-count"><?= $cat_counts[$c] ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

                <div class="sidebar-nav mt-md">
                    <a href="<?= SITE_URL ?>/archive" class="btn btn-outline btn-sm">
                        <i class="fas fa-arrow-left"></i> All Issues
                    </a>
                </div>
            </aside>

            <!-- Main: Article list -->
            <div class="articles-main">
                <?php if (empty($articles)): ?>
                    <p class="text-muted">No articles found<?= $cat ? ' in "' . e($cat) . '"' : '' ?>.</p>
                <?php else: ?>
                    <p class="result-count text-muted mb-md">
                        <?php
                        $from = $offset + 1;
                        $to   = min($offset + $per_page, $total_articles);
                        echo "Showing {$from}–{$to} of {$total_articles} article" . ($total_articles !== 1 ? 's' : '');
                        echo $cat ? ' in <strong>' . e($cat) . '</strong>' : '';
                        ?>
                    </p>

                    <ol class="article-list" reversed>
                        <?php foreach ($articles as $a): ?>
                        <li class="article-card">
                            <?php if (!empty($a['category'])): ?>
                                <span class="badge badge-gold"><?= e($a['category']) ?></span>
                            <?php endif; ?>

                            <h2 class="article-title">
                                <a href="<?= SITE_URL ?>/article?id=<?= $a['id'] ?>">
                                    <?= e($a['title']) ?>
                                </a>
                            </h2>

                            <p class="article-authors">
                                <i class="fas fa-user-pen"></i>
                                <?= e($a['authors']) ?>
                            </p>

                            <?php if (!empty(trim($a['abstract']))): ?>
                            <p class="article-abstract">
                                <?= e(mb_strimwidth(strip_tags($a['abstract']), 0, 280, '…')) ?>
                            </p>
                            <?php endif; ?>

                            <div class="article-meta">
                                <span><i class="fas fa-file-alt"></i> pp. <?= e($a['pages']) ?></span>
                                <span><i class="fas fa-calendar-alt"></i> <?= e((string)$a['year']) ?></span>
                            </div>

                            <div class="article-actions">
                                <a href="<?= SITE_URL ?>/article?id=<?= $a['id'] ?>" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> View Abstract
                                </a>
                                <?php if (!empty($a['pdf_upload'])): ?>
                                <a href="<?= e(SITE_URL . '/' . ltrim($a['pdf_upload'], '/')) ?>"
                                   target="_blank" rel="noopener"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </a>
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ol>

                    <?php if ($total_pages > 1): ?>
                    <nav class="pagination" aria-label="Page navigation">
                        <?php
                        $base_url = '?volume=' . $volume . '&year=' . $year . ($cat ? '&cat=' . urlencode($cat) : '');
                        ?>
                        <?php if ($page > 1): ?>
                            <a href="<?= $base_url ?>&p=<?= $page - 1 ?>" class="page-btn">&laquo; Prev</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="<?= $base_url ?>&p=<?= $i ?>"
                               class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="<?= $base_url ?>&p=<?= $page + 1 ?>" class="page-btn">Next &raquo;</a>
                        <?php endif; ?>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>