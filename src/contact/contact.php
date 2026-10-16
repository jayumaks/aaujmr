<?php
$page_title = 'Contact Us';
$page_css   = ['content-page.css'];
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-envelope"></i> Get in touch</p>
        <h1>Contact Us</h1>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <div class="prose-layout">
            <article class="prose">

                <div class="prose-section">
                    <h2>Editorial Office</h2>
                    <p>
                        For manuscript submissions, editorial inquiries, or general questions
                        about the journal, please use the contact details below.
                    </p>
                    <dl class="contact-block">
                        <dt>Editor-in-Chief</dt>
                        <dd><strong><?php echo e(setting('editor_name')); ?></strong></dd>
                        <dd><?php echo nl2br(e(setting('contact_address'))); ?></dd>
                        <dt>Email</dt>
                        <dd><a href="mailto:<?php echo e(setting('contact_email')); ?>"><?php echo e(setting('contact_email')); ?></a></dd>
                        <dt>Phone</dt>
                        <dd><a href="tel:<?php echo e(preg_replace('/[^\d+]/', '', setting('contact_phone'))); ?>"><?php echo e(setting('contact_phone')); ?></a></dd>
                        <?php if (setting('whatsapp_url')): ?>
                        <dt>WhatsApp</dt>
                        <dd><a href="<?php echo e(setting('whatsapp_url')); ?>" target="_blank" rel="noopener">Chat on WhatsApp</a></dd>
                        <?php endif; ?>
                    </dl>
                </div>

                <div class="prose-section">
                    <h2>Submission &amp; Review Queries</h2>
                    <p>
                        Authors wishing to submit a manuscript or enquire about the status of a
                        submission should contact the Editorial Office via the details above.
                        Please include your manuscript reference number (if applicable) in all
                        correspondence.
                    </p>
                    <a href="<?= SITE_URL ?>/submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> How to Submit
                    </a>
                </div>

            </article>

            <aside class="prose-sidebar">
                <div class="sidebar-widget">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?= SITE_URL ?>/submit"><i class="fas fa-paper-plane"></i> Submit Manuscript</a></li>
                        <li><a href="<?= SITE_URL ?>/guide"><i class="fas fa-book-open"></i> Author Guidelines</a></li>
                        <li><a href="<?= SITE_URL ?>/publication-fees"><i class="fas fa-file-invoice-dollar"></i> Subscription &amp; Advert Information</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
