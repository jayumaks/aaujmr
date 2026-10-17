<?php
$page_title = 'Home';
$page_css   = ['index-home.css', 'articles.css'];
require_once __DIR__ . '/../layout/header.php';

// Fetch latest issue stats for the hero section
try {
    $stats = db()->query("
        SELECT
            (SELECT COUNT(*) FROM manuscripts) AS total_articles,
            (SELECT COUNT(DISTINCT volume) FROM manuscripts) AS total_volumes,
            (SELECT MAX(year) FROM manuscripts) AS latest_year
    ")->fetch();
} catch (PDOException $e) {
    $stats = ['total_articles' => 0, 'total_volumes' => 0, 'latest_year' => date('Y')];
}

// Recent articles for homepage section
// try {
//     $recent_articles = db()->query(
//         'SELECT id, title, authors, abstract, year, volume, issue, category
//          FROM manuscripts ORDER BY year DESC, volume DESC, id DESC LIMIT 4'
//     )->fetchAll();
// } catch (PDOException $e) {
//     $recent_articles = [];
// }
?>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <ul class="hero-stickers">
                <li><i class="fas fa-microscope"></i> Peer-reviewed</li>
                <li><i class="fas fa-globe"></i> International scope</li>
                <li><i class="fas fa-calendar-check"></i> Published every June</li>
            </ul>
            <h1 class="hero-title">Ambrose Alli University Journal of Medical Research</h1>
            <p class="hero-subtitle">
                A Publication of the Faculty of Basic Medical Sciences, College of Medicine,
                Ambrose Alli University, Ekpoma, Nigeria
            </p>
            <div class="hero-actions">
                <a href="<?= SITE_URL ?>/current-issue" class="btn btn-gold">
                    <i class="fas fa-newspaper"></i> Browse Current Issue
                </a>
                <a href="<?= SITE_URL ?>/submit" class="btn btn-outline-white">
                    <i class="fas fa-paper-plane"></i> Submit Manuscript
                </a>
            </div>
        </div>
    </div>
    <?php if (!empty($latest['volume'])): ?>
    <a class="hero-issue" href="<?= SITE_URL ?>/current-issue">
        <span class="hero-issue-label">Latest issue</span>
        <span class="hero-issue-vol">Vol. <?= e((string)$latest['volume']) ?></span>
        <span class="hero-issue-year"><?= e((string)$latest['year']) ?></span>
        <span class="hero-issue-go">Read now</span>
    </a>
    <?php endif; ?>
</section>

<!-- Stats bar -->
<?php if ($stats['total_articles'] > 0): ?>
<div class="stats-bar">
    <div class="container">
        <div class="stats-row">
            <div class="stat">
                <span class="stat-num"><?= $stats['total_articles'] ?></span>
                <span class="stat-label">Articles Published</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-num"><?= $stats['total_volumes'] ?></span>
                <span class="stat-label">Volumes</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-num"><?= $stats['latest_year'] ?></span>
                <span class="stat-label">Latest Year</span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- About the journal -->
<section class="about-section content-page">
    <div class="container">
        <div class="about-grid">
            <div class="about-text">
                <span class="section-label">About the Journal</span>
                <h2>A platform for medical and scientific research in Nigeria and beyond</h2>
                <p>
                    The <em>Ambrose Alli University Journal of Medical Research (AAUJMR)</em> is the publication of the Faculty of Basic Medical Sciences, College of Medicine, Ambrose Alli University, Ekpoma, Nigeria.
                </p>
                <p>
                    The Journal publishes original research papers, review articles, techniques, short reports, case reports, letters, conference proceedings and book reviews. It is published annually (June).
                </p>
                <a href="<?= SITE_URL ?>/aims-scope" class="btn btn-primary mt-md">
                    <i class="fas fa-arrow-right"></i> Read Aims &amp; Scope
                </a>
            </div>

            <!-- Scope cards -->
            <div class="scope-grid">
                <?php
                $scopes = [
                    ['icon' => 'fa-microscope', 'label' => 'Basic & Clinical Medicine', 'desc' => 'Research across Basic and Clinical Medicine.'],
                    ['icon' => 'fa-dna', 'label' => 'Life & Biological Sciences', 'desc' => 'Research in life sciences and biological sciences.'],
                    ['icon' => 'fa-leaf', 'label' => 'Environmental & Natural Sciences', 'desc' => 'Environmental and natural sciences research.'],
                    ['icon' => 'fa-flask', 'label' => 'Physical & Chemical Sciences', 'desc' => 'Research in physical and chemical sciences.'],
                ];
                foreach ($scopes as $s): ?>
                <div class="scope-card">
                    <div class="scope-icon"><i class="fas <?= $s['icon'] ?>"></i></div>
                    <h4><?= $s['label'] ?></h4>
                    <p><?= $s['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Recent Articles -->
<?php if (!empty($recent_articles)): ?>
<section class="content-page" style="padding-top:3rem;padding-bottom:1rem">
    <div class="container">
        <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:1.5rem">
            <h2 style="font-family:var(--font-heading)">Recent Articles</h2>
            <a href="<?= SITE_URL ?>/archive" class="btn btn-outline btn-sm"><i class="fas fa-archive"></i> All Issues</a>
        </div>
        <ol class="article-list" style="counter-reset:none" reversed>
            <?php foreach ($recent_articles as $ra): ?>
            <li class="article-card">
                <?php if (!empty($ra['category'])): ?>
                    <span class="badge badge-gold"><?= e($ra['category']) ?></span>
                <?php endif; ?>
                <h2 class="article-title">
                    <a href="<?= SITE_URL ?>/article?id=<?= $ra['id'] ?>"><?= e($ra['title']) ?></a>
                </h2>
                <p class="article-authors"><i class="fas fa-user-pen"></i> <?= e($ra['authors']) ?></p>
                <?php if (!empty(trim($ra['abstract']))): ?>
                <p class="article-abstract"><?= e(mb_strimwidth(strip_tags($ra['abstract']), 0, 220, '…')) ?></p>
                <?php endif; ?>
                <div class="article-meta">
                    <span><i class="fas fa-book"></i> Vol. <?= e((string)$ra['volume']) ?>, Issue <?= e($ra['issue']) ?></span>
                    <span><i class="fas fa-calendar-alt"></i> <?= e((string)$ra['year']) ?></span>
                </div>
                <div class="article-actions">
                    <a href="<?= SITE_URL ?>/article?id=<?= $ra['id'] ?>" class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i> View Abstract
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
<?php endif; ?>

<!-- Call to action -->
<section class="cta-banner">
    <div class="container">
        <div class="cta-inner">
            <div>
                <h2>Ready to publish your research?</h2>
                <p>Review our author guidelines and submit your manuscript today.</p>
            </div>
            <div class="cta-btns">
                <a href="<?= SITE_URL ?>/guide" class="btn btn-gold">
                    <i class="fas fa-book-open"></i> Author Guidelines
                </a>
                <a href="<?= SITE_URL ?>/submit" class="btn btn-outline-white">
                    <i class="fas fa-paper-plane"></i> Submit Now
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
