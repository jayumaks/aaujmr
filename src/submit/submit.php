<?php
$page_title = 'Submit Manuscript';
$page_css   = ['content-page.css'];
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-paper-plane"></i> Authors</p>
        <h1>Submit a Manuscript</h1>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="prose-layout">
            <article class="prose">
                <div class="prose-section">
                    <h2>Submission by Email</h2>
                    <p>The manuscript should be submitted to the Editor-in-chief via electronic mail using either of the following addresses:</p>
                    <ul>
                        <li><a href="mailto:aaujmr@aauekpoma.edu.ng">aaujmr@aauekpoma.edu.ng</a></li>
                        <li><a href="mailto:editoraaujmr@gmail.com">editoraaujmr@gmail.com</a></li>
                    </ul>
                </div>

                <div class="prose-section">
                    <h2>Manuscript Requirements</h2>
                    <p>Please ensure that your manuscript follows the <a href="<?= SITE_URL ?>/guide">Instructions to Authors</a>, including double spacing, 12-point Times New Roman font, page numbering, an abstract of not more than 250 words, 3–5 keywords and Vancouver-style references.</p>
                </div>

                <div class="prose-section">
                    <h2>Correspondence Address</h2>
                    <p>Inquiries concerning advert and rates should be addressed to:</p>
                    <dl class="contact-block">
                        <dt>Editor-in-Chief</dt>
                        <dd>Ambrose Alli University Journal of Medical Research (AAUJMR)</dd>
                        <dd>Office of the Dean, Faculty of Basic Medical Sciences,<br>Ambrose Alli University, Ekpoma, Nigeria.</dd>
                        <dt>Email</dt>
                        <dd><a href="mailto:aaujmr@aauekpoma.edu.ng">aaujmr@aauekpoma.edu.ng</a></dd>
                        <dd><a href="mailto:editoraaujmr@gmail.com">editoraaujmr@gmail.com</a></dd>
                    </dl>
                </div>
            </article>

            <aside class="prose-sidebar">
                <div class="sidebar-widget">
                    <h3>Before You Submit</h3>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/guide"><i class="fas fa-book-open"></i> Instructions to Authors</a></li>
                        <li><a href="<?= SITE_URL ?>/aims-scope"><i class="fas fa-bullseye"></i> Aims &amp; Scope</a></li>
                        <li><a href="<?= SITE_URL ?>/ethics-policies"><i class="fas fa-scale-balanced"></i> Ethics Policy</a></li>
                    </ul>
                </div>
                <div class="sidebar-widget">
                    <h3>Publication Schedule</h3>
                    <p><strong>Annual publication:</strong><br>June</p>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
