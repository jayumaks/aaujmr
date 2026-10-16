<?php
$page_title = 'Subscription & Advert Information';
$page_css   = ['content-page.css'];
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-newspaper"></i> Journal Information</p>
        <h1>Subscription &amp; Advert Information</h1>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="prose-layout">
            <article class="prose">
                <div class="prose-section">
                    <h2>Subscription Information</h2>
                    <p>AAUJMR is published annually in June. For subscription enquiries, advert enquiries and current rates, please contact the Editor-in-Chief.</p>
                </div>

                <div class="prose-section">
                    <h2>Advert and Rate Enquiries</h2>
                    <p>Inquiries concerning advert and rates should be to:</p>
                    <div class="contact-block">
                        <p><strong>The Editor-in-Chief</strong><br>
                        Ambrose Alli University Journal of Medical Research (AAUJMR)<br>
                        Office of the Dean, Faculty of Basic Medical Sciences,<br>
                        Ambrose Alli University, Ekpoma, Nigeria.</p>
                        <p><a href="mailto:aaujmr@aauekpoma.edu.ng">aaujmr@aauekpoma.edu.ng</a><br>
                        <a href="mailto:editoraaujmr@gmail.com">editoraaujmr@gmail.com</a></p>
                    </div>
                </div>

                <div class="prose-section">
                    <h2>Publication Schedule</h2>
                    <p><strong>Frequency:</strong> Annually</p>
                    <p><strong>Publication month:</strong> June</p>
                </div>
            </article>

            <aside class="prose-sidebar">
                <div class="sidebar-widget">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/submit"><i class="fas fa-paper-plane"></i> Submit Manuscript</a></li>
                        <li><a href="<?= SITE_URL ?>/guide"><i class="fas fa-book-open"></i> Instructions to Authors</a></li>
                        <li><a href="<?= SITE_URL ?>/contact"><i class="fas fa-envelope"></i> Contact Us</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
