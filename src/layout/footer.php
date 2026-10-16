</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <span class="brand-abbr-lg">AAUJMR</span>
                <p><?php echo nl2br(e(setting('contact_address', "Faculty of Basic Medical Sciences\nAmbrose Alli University\nP.M.B. 14, Ekpoma, Edo State, Nigeria"))); ?></p>
            </div>
            <div class="footer-links">
                <h4>Journal</h4>
                <ul>
                    <li><a href="<?= SITE_URL ?>/current-issue">Current Issue</a></li>
                    <li><a href="<?= SITE_URL ?>/archive">Archives</a></li>
                    <li><a href="<?= SITE_URL ?>/aims-scope">Aims &amp; Scope</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Authors</h4>
                <ul>
                    <li><a href="<?= SITE_URL ?>/guide">Author Guidelines</a></li>
                    <li><a href="<?= SITE_URL ?>/submit">Submit Manuscript</a></li>
                    <li><a href="<?= SITE_URL ?>/publication-fees">Subscription &amp; Advert Information</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>About</h4>
                <ul>
                    <li><a href="<?= SITE_URL ?>/board">Editorial Board</a></li>
                    <li><a href="<?= SITE_URL ?>/ethics-policies">Ethics &amp; Policies</a></li>
                    <li><a href="<?= SITE_URL ?>/contact">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>Copyright &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</span>
            <div class="footer-socials">
                <?php if (setting('social_twitter')): ?>
                <a href="<?php echo e(setting('social_twitter')); ?>" aria-label="Twitter / X" target="_blank" rel="noopener"><i class="fa-brands fa-x-twitter"></i></a>
                <?php endif; ?>
                <?php if (setting('social_linkedin')): ?>
                <a href="<?php echo e(setting('social_linkedin')); ?>" aria-label="LinkedIn" target="_blank" rel="noopener"><i class="fa-brands fa-linkedin-in"></i></a>
                <?php endif; ?>
                <?php if (setting('social_whatsapp')): ?>
                <a href="<?php echo e(setting('social_whatsapp')); ?>" aria-label="WhatsApp" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
