<?php
$page_title = 'About the Journal & Aims and Scope';
$page_css   = ['content-page.css'];
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-bullseye"></i> About the Journal</p>
        <h1>Aims &amp; Scope</h1>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="prose-layout">
            <article class="prose">
                <div class="prose-section">
                    <h2>About the Journal</h2>
                    <p>The Ambrose Alli University Journal of Medical Research (AAUJMR) is the publication of the Faculty of Basic Medical Sciences, College of Medicine, Ambrose Alli University, Ekpoma, Nigeria.</p>
                    <p>The Journal publishes original research papers, review articles, techniques, short reports, case reports, letters, conference proceedings and book reviews. It is published annually (June).</p>
                </div>

                <div class="prose-section">
                    <h2>Aim and Scope</h2>
                    <p>The Ambrose Alli University Journal of Medical Research (AAUJMR) is an international scope peer-review journal. The journal publishes articles written in English in all aspects of Basic and Clinical Medicine, Life and Biological Sciences, Environmental, Natural, Physical and Chemical Sciences.</p>
                </div>

                <div class="prose-section">
                    <h2>Article Types</h2>
                    <ul>
                        <li>Original research papers</li>
                        <li>Review articles</li>
                        <li>Techniques</li>
                        <li>Short reports</li>
                        <li>Case reports</li>
                        <li>Letters</li>
                        <li>Conference proceedings</li>
                        <li>Book reviews</li>
                    </ul>
                </div>

                <div class="prose-section">
                    <h2>Publication Schedule</h2>
                    <p>AAUJMR is published <strong>annually in June</strong>.</p>
                </div>
            </article>

            <aside class="prose-sidebar">
                <div class="sidebar-widget">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/guide"><i class="fas fa-book-open"></i> Author Guidelines</a></li>
                        <li><a href="<?= SITE_URL ?>/submit"><i class="fas fa-paper-plane"></i> Submit Manuscript</a></li>
                        <li><a href="<?= SITE_URL ?>/board"><i class="fas fa-users"></i> Editorial Board</a></li>
                        <li><a href="<?= SITE_URL ?>/ethics-policies"><i class="fas fa-scale-balanced"></i> Ethics &amp; Policies</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
