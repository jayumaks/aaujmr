<?php
require_once __DIR__ . '/../config/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) redirect('/archive');

$stmt = db()->prepare("SELECT * FROM manuscripts WHERE id = ?");
$stmt->execute([$id]);
$a = $stmt->fetch();

if (!$a) {
    http_response_code(404);
    $page_title = 'Not Found';
    $page_css   = ['article.css'];
    include __DIR__ . '/../layout/header.php';
    ?><section class="content-page"><div class="container" style="padding:3rem 0">
        <p class="text-muted">Article not found. <a href="<?= SITE_URL ?>/archive">Browse the archive</a>.</p>
    </div></section><?php
    include __DIR__ . '/../layout/footer.php';
    exit;
}

$page_title       = $a['title'];
$page_description = trim($a['abstract'])
    ? mb_strimwidth(strip_tags($a['abstract']), 0, 155, '…')
    : e($a['authors']) . ' — ' . SITE_NAME;
$page_css         = ['article.css'];
include __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container">
        <p class="subtitle">
            <a href="<?= SITE_URL ?>/articles?volume=<?= $a['volume'] ?>&year=<?= $a['year'] ?><?= !empty($a['issue']) ? '&issue=' . urlencode($a['issue']) : '' ?>"
               style="color:rgba(255,255,255,.7)">
                <i class="fas fa-arrow-left"></i>
                Volume <?= e((string)$a['volume']) ?>, <?= e((string)$a['year']) ?>
            </a>
        </p>
        <?php if (!empty($a['category'])): ?>
            <span class="badge badge-gold" style="margin:.75rem 0;display:inline-block">
                <?= e($a['category']) ?>
            </span>
        <?php endif; ?>
        <h1 style="font-size:clamp(1.2rem,3vw,1.75rem);font-weight:600;max-width:900px">
            <?= e($a['title']) ?>
        </h1>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="article-detail-layout">

            <article class="article-body">
                <div class="detail-block">
                    <h3 class="detail-label"><i class="fas fa-user-pen"></i> Authors</h3>
                    <p class="detail-value"><?= e($a['authors']) ?></p>
                </div>

                <?php if (!empty(trim($a['abstract']))): ?>
                <div class="detail-block">
                    <h3 class="detail-label"><i class="fas fa-align-left"></i> Abstract</h3>
                    <div class="abstract-text"><?= nl2br(e($a['abstract'])) ?></div>
                </div>
                <?php endif; ?>

                <?php if (!empty($a['pdf_upload'])): ?>
                <div class="pdf-download-block">
                    <i class="fas fa-file-pdf pdf-icon"></i>
                    <div>
                        <p class="pdf-label">Full Article (PDF)</p>
                        <a href="<?= e(SITE_URL . '/' . ltrim($a['pdf_upload'], '/')) ?>"
                           target="_blank" rel="noopener"
                           class="btn btn-primary">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </article>

            <aside class="article-meta-sidebar">
                <div class="meta-card">
                    <h3 class="meta-card-title">Article Details</h3>
                    <dl class="meta-list">
                        <dt>Journal</dt><dd><?= SITE_NAME ?></dd>
                        <dt>Volume</dt><dd><?= e((string)$a['volume']) ?></dd>
                        <?php if (!empty($a['issue'])): ?>
                        <dt>Issue</dt><dd><?= e($a['issue']) ?></dd>
                        <?php endif; ?>
                        <dt>Year</dt><dd><?= e((string)$a['year']) ?></dd>
                        <dt>Pages</dt><dd><?= e($a['pages']) ?></dd>
                        <?php if (!empty($a['category'])): ?>
                        <dt>Category</dt><dd><?= e($a['category']) ?></dd>
                        <?php endif; ?>
                        <?php if (!empty($a['email'])): ?>
                        <dt>Corresponding</dt>
                        <dd><a href="mailto:<?= e($a['email']) ?>"><?= e($a['email']) ?></a></dd>
                        <?php endif; ?>
                    </dl>
                </div>
                <a href="<?= SITE_URL ?>/articles?volume=<?= $a['volume'] ?>&year=<?= $a['year'] ?><?= !empty($a['issue']) ? '&issue=' . urlencode($a['issue']) : '' ?>"
                   class="btn btn-outline btn-sm"
                   style="width:100%;justify-content:center;margin-top:1rem">
                    <i class="fas fa-arrow-left"></i> Back to Issue
                </a>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>