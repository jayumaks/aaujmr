<?php
http_response_code(404);
$page_title       = 'Page Not Found';
$page_description = 'The page you requested could not be found on the Ambrose Alli University Journal of Medical Research website.';
$page_css         = ['content-page.css'];
require_once __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-exclamation-triangle"></i> Error 404</p>
        <h1>Page Not Found</h1>
    </div>
</div>

<section class="content-page">
    <div class="container" style="max-width:640px;text-align:center;padding:3rem 1rem">
        <p style="font-size:5rem;line-height:1;margin-bottom:1rem;color:var(--maroon)">404</p>
        <p style="font-size:1.2rem;margin-bottom:1.5rem">
            Sorry, the page you were looking for does not exist or may have been moved.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
            <a href="<?= SITE_URL ?>/" class="btn btn-primary">
                <i class="fas fa-home"></i> Go to Homepage
            </a>
            <a href="<?= SITE_URL ?>/archive" class="btn btn-outline">
                <i class="fas fa-archive"></i> Browse Archives
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
