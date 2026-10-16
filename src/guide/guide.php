<?php
$page_title = 'Instructions to Authors';
$page_css   = ['content-page.css'];
require_once __DIR__ . '/../layout/header.php';
?>
<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-book-open"></i> Authors</p>
        <h1>Instructions to Authors</h1>
    </div>
</div>
<section class="content-page">
    <div class="container">
        <div class="prose-layout">
            <article class="prose">
                <div class="prose-section">
                    <h2>Conditions for Submission of Manuscript</h2>
                    <p>Submission of an article/manuscript implies that the finding(s) described has/have not been published before, is not under consideration elsewhere for publication and that author(s) and relevant authorities (if any) approved of its publication.</p>
                    <p>Also, article/manuscript submitted for publication involving human or animal should have approval from relevant bodies and institutional research ethic committee. Participants must give informed consent and animal experiments must be conducted in compliance with the guidelines and regulations of the National Institute of Health for laboratory animal care and use.</p>
                    <p>It is the responsibility of the author(s) to obtain permission to reproduce or use illustrations, figures or tables from other publications.</p>
                </div>

                <div class="prose-section">
                    <h2>Manuscript Format</h2>
                    <p>The manuscript should be submitted to the Editor-in-chief via electronic mail at <a href="mailto:aaujmr@aauekpoma.edu.ng">aaujmr@aauekpoma.edu.ng</a> or <a href="mailto:editoraaujmr@gmail.com">editoraaujmr@gmail.com</a>.</p>
                    <p>The typed manuscript should be <strong>double spaced</strong>, <strong>12 point font size</strong> and <strong>Times New Roman</strong> font face.</p>
                    <p>The pages should be numbered and arranged in the following order:</p>
                    <ol>
                        <li>Title page (which should also contain the affiliation(s) of author(s) and address of corresponding author)</li>
                        <li>Abstract (not more than 250 words)</li>
                        <li>Keywords (3 to 5)</li>
                        <li>Text</li>
                        <li>Acknowledgements</li>
                        <li>Conflict of interest</li>
                        <li>References</li>
                    </ol>
                    <p><strong>Note:</strong> Tables, figures and legends should appear in their right spaces.</p>
                    <p>The references within the text should be cited by Arabic numbers and should correlate with a source in the list of references using the <strong>Vancouver style</strong>.</p>
                </div>

                <div class="prose-section">
                    <h2>Review Process</h2>
                    <p>All submitted articles/manuscripts will be peer reviewed after initial review by the Editor-in-Chief and Editorial Board.</p>
                </div>

                <div class="prose-section">
                    <h2>Submission Checklist</h2>
                    <ul>
                        <li>The manuscript is original and is not under consideration elsewhere.</li>
                        <li>All relevant ethical approvals have been obtained where applicable.</li>
                        <li>Human participants have given informed consent where applicable.</li>
                        <li>The manuscript follows the required format, font and spacing.</li>
                        <li>Abstract is not more than 250 words and includes 3–5 keywords.</li>
                        <li>References follow Vancouver style.</li>
                    </ul>
                </div>
            </article>
            <aside class="prose-sidebar">
                <div class="sidebar-widget">
                    <h3>For Authors</h3>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/submit"><i class="fas fa-paper-plane"></i> Submit Manuscript</a></li>
                        <li><a href="<?= SITE_URL ?>/aims-scope"><i class="fas fa-bullseye"></i> Aims &amp; Scope</a></li>
                        <li><a href="<?= SITE_URL ?>/ethics-policies"><i class="fas fa-scale-balanced"></i> Ethics and Policies</a></li>
                        <li><a href="<?= SITE_URL ?>/contact"><i class="fas fa-envelope"></i> Contact Editors</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
