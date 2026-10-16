<?php
$page_title = 'Ethics & Policies';
$page_css   = ['content-page.css'];
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-scale-balanced"></i> About the Journal</p>
        <h1>Ethics &amp; Policies</h1>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="prose-layout">
            <article class="prose">

                <div class="prose-section">
                    <h2>Ethical Standards</h2>
                    <p>
                        The <em>Ambrose Alli University Journal of Medical Research (AAUJMR)</em> is committed to upholding the highest
                        standards of publication ethics. All parties — authors, peer reviewers, and editors
                        — are expected to adhere to these standards. AAUJMR follows the guidelines established
                        by the <strong>Committee on Publication Ethics (COPE)</strong>.
                    </p>
                </div>

                <div class="prose-section">
                    <h2>Authorship</h2>
                    <ul>
                        <li>All listed authors must have made a substantial contribution to the conception, design, execution, or interpretation of the reported study.</li>
                        <li>All authors must have approved the final version of the manuscript and agreed to be accountable for all aspects of the work.</li>
                        <li>Ghost authorship and gift authorship are not permitted.</li>
                        <li>Changes to authorship after submission must be approved in writing by all listed authors and the Editor-in-Chief.</li>
                    </ul>
                </div>

                <div class="prose-section">
                    <h2>Research Involving Human Subjects</h2>
                    <p>
                        Studies involving human participants must have been approved by an appropriate
                        Institutional Review Board (IRB) or Research Ethics Committee (REC).
                        Authors must include a statement confirming ethical approval and informed consent
                        in the Methods section of the manuscript.
                    </p>
                </div>

                <div class="prose-section">
                    <h2>Research Involving Animals</h2>
                    <p>
                        Animal studies must comply with applicable national welfare regulations and must
                        have received institutional approval. The ARRIVE guidelines should be followed
                        where appropriate.
                    </p>
                </div>

                <div class="prose-section">
                    <h2>Plagiarism Policy</h2>
                    <p>
                        AAUJMR does not tolerate plagiarism in any form. All submitted manuscripts are
                        screened for originality. Manuscripts found to contain plagiarised content
                        will be immediately rejected. Previously published authors found to have
                        plagiarised may have their articles retracted.
                    </p>
                </div>

                <div class="prose-section">
                    <h2>Conflicts of Interest</h2>
                    <p>
                        Authors must disclose any financial, personal, or professional relationships
                        that could be perceived as influencing the work. Conflict of interest statements
                        must be included in the manuscript. Reviewers who have a conflict of interest
                        with a submitted manuscript must recuse themselves.
                    </p>
                </div>

                <div class="prose-section">
                    <h2>Peer Review Policy</h2>
                    <p>
                        All submitted articles/manuscripts will be peer reviewed after initial review by the Editor-in-Chief and Editorial Board.
                    </p>
                </div>

                <div class="prose-section">
                    <h2>Corrections &amp; Retractions</h2>
                    <p>
                        When significant errors are identified in published articles, AAUJMR will publish a
                        correction notice. Articles found to have been fraudulent, plagiarised, or to
                        violate research ethics will be retracted, with a clear retraction notice linked
                        to the original article.
                    </p>
                </div>

                <div class="prose-section">
                    <h2>Data Availability</h2>
                    <p>
                        Authors are encouraged to make their underlying research data available. Where
                        data sharing is not possible, authors should explain why in their manuscript.
                    </p>
                </div>

            </article>

            <aside class="prose-sidebar">
                <div class="sidebar-widget">
                    <h3>Related Policies</h3>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/guide"><i class="fas fa-book-open"></i> Author Guidelines</a></li>
                        <li><a href="<?= SITE_URL ?>/aims-scope"><i class="fas fa-bullseye"></i> Aims &amp; Scope</a></li>
                        <li><a href="<?= SITE_URL ?>/contact"><i class="fas fa-envelope"></i> Contact Editorial Office</a></li>
                    </ul>
                </div>
                <div class="sidebar-widget">
                    <h3>External Resources</h3>
                    <ul>
                        <li><a href="https://publicationethics.org" target="_blank" rel="noopener">COPE Guidelines <i class="fas fa-external-link-alt fa-xs"></i></a></li>
                        <li><a href="https://www.equator-network.org" target="_blank" rel="noopener">EQUATOR Network <i class="fas fa-external-link-alt fa-xs"></i></a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
