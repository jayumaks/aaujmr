<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_admin();

const SETTINGS_KEYS = [
    'editor_name', 'contact_email', 'submission_email', 'submission_email_secondary', 'contact_phone',
    'whatsapp_url', 'contact_address',
    'social_twitter', 'social_linkedin', 'social_whatsapp',
    'pub_frequency', 'publication_month',
];

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
$pw_error = $pw_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { $_SESSION['flash'] = 'Invalid request.'; redirect('/admin-settings'); }

    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        $cur  = $_POST['current_password'] ?? '';
        $new  = $_POST['new_password']     ?? '';
        $conf = $_POST['confirm_password'] ?? '';
        $row  = db()->prepare('SELECT id, password FROM user_accounts WHERE id = ?');
        $row->execute([$_SESSION['user_id'] ?? 0]);
        $admin = $row->fetch();
        if (!$admin || !password_verify($cur, $admin['password'])) {
            $pw_error = 'Current password is incorrect.';
        } elseif (strlen($new) < 8) {
            $pw_error = 'New password must be at least 8 characters.';
        } elseif ($new !== $conf) {
            $pw_error = 'New passwords do not match.';
        } else {
            db()->prepare('UPDATE user_accounts SET password = ? WHERE id = ?')
               ->execute([password_hash($new, PASSWORD_DEFAULT), $admin['id']]);
            $pw_success = 'Password updated successfully.';
        }
    } else {
        $stmt = db()->prepare(
            'INSERT INTO site_settings (`key`, `value`) VALUES (?,?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
        );
        foreach (SETTINGS_KEYS as $k) {
            $stmt->execute([$k, trim($_POST[$k] ?? '')]);
        }
        $_SESSION['flash'] = 'Settings saved successfully.';
        redirect('/admin-settings');
    }
}

$page_title = 'Site Settings';
$page_css   = ['admin.css'];
include __DIR__ . '/../layout/header.php';
?>

<div class="page-banner">
    <div class="container" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
        <div>
            <h1><i class="fas fa-sliders"></i> Site Settings</h1>
            <p class="subtitle">Manage contact details, social links and publication schedule</p>
        </div>
        <a href="<?= SITE_URL ?>/admin" class="btn btn-outline btn-sm" style="margin-top:.5rem;color: white;border-color: white;">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<section class="content-page">
  <div class="container">

    <?php if ($flash): ?>
      <div class="alert alert-success"><?php echo e($flash); ?></div>
    <?php endif; ?>

    <form method="POST">
      <?php echo csrf_field(); ?>

      <!-- ── Contact & Communications ───────────────────────────────────── -->
      <div class="admin-section">
        <div class="admin-section-header">
          <h2><i class="fas fa-envelope"></i> Contact &amp; Communications</h2>
        </div>
        <div style="padding:1.25rem 1.5rem">
          <div class="form-row-2">
            <div class="form-group">
              <label>Editor-in-Chief Name</label>
              <input type="text" name="editor_name" class="form-control"
                     value="<?php echo e(setting('editor_name')); ?>">
            </div>
            <div class="form-group">
              <label>Editorial Office Email</label>
              <input type="email" name="contact_email" class="form-control"
                     value="<?php echo e(setting('contact_email')); ?>">
            </div>
          </div>
          <div class="form-row-2">
            <div class="form-group">
              <label>Manuscript Submission Email
                <small class="text-muted">(shown on Submit page)</small>
              </label>
              <input type="email" name="submission_email" class="form-control"
                     placeholder="aaujmr@aauekpoma.edu.ng"
                     value="<?php echo e(setting('submission_email')); ?>">
            </div>
            <div class="form-group">
              <label>Second Manuscript Submission Email</label>
              <input type="email" name="submission_email_secondary" class="form-control"
                     placeholder="editoraaujmr@gmail.com"
                     value="<?php echo e(setting('submission_email_secondary')); ?>">
            </div>
          </div>
          <div class="form-row-2">
            <div class="form-group">
              <label>Contact Phone</label>
              <input type="text" name="contact_phone" class="form-control"
                     placeholder="+234 800 000 0000"
                     value="<?php echo e(setting('contact_phone')); ?>">
            </div>
            <div class="form-group">
              <label>WhatsApp Link</label>
              <input type="url" name="whatsapp_url" class="form-control"
                     placeholder="https://wa.link/..."
                     value="<?php echo e(setting('whatsapp_url')); ?>">
            </div>
          </div>
          <div class="form-group">
            <label>Postal Address
              <small class="text-muted">(each address line on a new line)</small>
            </label>
            <textarea name="contact_address" class="form-control" rows="3"
            ><?php echo e(setting('contact_address')); ?></textarea>
          </div>
        </div>
      </div>

      <!-- ── Social Media ──────────────────────────────────────────────────── -->
      <div class="admin-section">
        <div class="admin-section-header">
          <h2><i class="fas fa-share-nodes"></i> Social Media Links</h2>
        </div>
        <div style="padding:1.25rem 1.5rem">
          <p class="text-muted" style="margin-bottom:1rem;font-size:.875rem">
            Leave a field empty to hide that icon in the site footer.
          </p>
          <div class="form-row-3">
            <div class="form-group">
              <label><i class="fa-brands fa-x-twitter"></i> Twitter / X URL</label>
              <input type="url" name="social_twitter" class="form-control"
                     placeholder="https://x.com/..."
                     value="<?php echo e(setting('social_twitter')); ?>">
            </div>
            <div class="form-group">
              <label><i class="fa-brands fa-linkedin-in"></i> LinkedIn URL</label>
              <input type="url" name="social_linkedin" class="form-control"
                     placeholder="https://linkedin.com/..."
                     value="<?php echo e(setting('social_linkedin')); ?>">
            </div>
            <div class="form-group">
              <label><i class="fa-brands fa-whatsapp"></i> WhatsApp URL (footer icon)</label>
              <input type="url" name="social_whatsapp" class="form-control"
                     placeholder="https://wa.link/..."
                     value="<?php echo e(setting('social_whatsapp')); ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- ── Publication Schedule ──────────────────────────────────────────── -->
      <div class="admin-section">
        <div class="admin-section-header">
          <h2><i class="fas fa-calendar-alt"></i> Publication Schedule</h2>
        </div>
        <div style="padding:1.25rem 1.5rem">
          <div class="form-row-2">
            <div class="form-group">
              <label>Publication Frequency</label>
              <input type="text" name="pub_frequency" class="form-control"
                     placeholder="Annually"
                     value="<?php echo e(setting('pub_frequency')); ?>">
            </div>
            <div class="form-group">
              <label>Publication Month</label>
              <input type="text" name="publication_month" class="form-control"
                     placeholder="June"
                     value="<?php echo e(setting('publication_month')); ?>">
            </div>
          </div>
        </div>
      </div>

      <div class="form-actions-bar">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Save All Settings
        </button>
        <a href="<?= SITE_URL ?>/admin" class="btn btn-outline">Cancel</a>
      </div>

    </form>
  </div>
</section>

<section class="content-page" style="padding-top:0">
  <div class="container">
    <div class="admin-section">
      <div class="admin-section-header">
        <h2><i class="fas fa-lock"></i> Change Admin Password</h2>
      </div>
      <div style="padding:1.25rem 1.5rem">
        <?php if ($pw_error): ?>
          <div class="alert alert-error"><?php echo e($pw_error); ?></div>
        <?php elseif ($pw_success): ?>
          <div class="alert alert-success"><?php echo e($pw_success); ?></div>
        <?php endif; ?>
        <form method="POST" style="max-width:480px">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="change_password">
          <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
          </div>
          <div class="form-group">
            <label>New Password <small class="text-muted">(min. 8 characters)</small></label>
            <input type="password" name="new_password" class="form-control" required minlength="8" autocomplete="new-password">
          </div>
          <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required autocomplete="new-password">
          </div>
          <div class="form-actions-bar">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-key"></i> Update Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
