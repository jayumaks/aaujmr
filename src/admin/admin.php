<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_admin();

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { $_SESSION['flash'] = 'Invalid request.'; redirect('/admin'); }
    $action = $_POST['action'] ?? '';

    if ($action === 'delete_articles') {
        $ids = array_map('intval', $_POST['delete_ids'] ?? []);
        if (!empty($ids)) {
            $ph = implode(',', array_fill(0, count($ids), '?'));
            $s = db()->prepare("SELECT pdf_upload FROM manuscripts WHERE id IN ($ph)");
            $s->execute($ids);
            foreach ($s->fetchAll() as $row) {
                if (!empty($row['pdf_upload'])) {
                    $p = dirname(__DIR__, 2) . '/' . $row['pdf_upload'];
                    if (file_exists($p)) @unlink($p);
                }
            }
            db()->prepare("DELETE FROM manuscripts WHERE id IN ($ph)")->execute($ids);
            $_SESSION['flash'] = count($ids) . ' article(s) deleted.';
        }
        redirect('/admin');
    }
    if ($action === 'edit_board') {
        $bid  = (int)($_POST['board_id']      ?? 0);
        $name = trim($_POST['name']           ?? '');
        $role = trim($_POST['role']           ?? '');
        $aff  = trim($_POST['affiliation']    ?? '');
        $ord  = (int)($_POST['display_order'] ?? 0);
        if ($bid > 0 && $name && $role) {
            db()->prepare('UPDATE editorial_board SET name=?,role=?,affiliation=?,display_order=? WHERE id=?')
               ->execute([$name, $role, $aff, $ord, $bid]);
            $_SESSION['flash'] = 'Board member updated.';
        }
        redirect('/admin#board');
    }
    if ($action === 'add_board') {
        $name  = trim($_POST['name']  ?? '');
        $role  = trim($_POST['role']  ?? '');
        $aff   = trim($_POST['affiliation'] ?? '');
        $ord   = (int)($_POST['display_order'] ?? 0);
        if ($name && $role) {
            db()->prepare('INSERT INTO editorial_board (name,role,affiliation,display_order) VALUES (?,?,?,?)')->execute([$name,$role,$aff,$ord]);
            $_SESSION['flash'] = 'Board member added.';
        }
        redirect('/admin#board');
    }
    if ($action === 'delete_board') {
        $bid = (int)($_POST['board_id'] ?? 0);
        if ($bid > 0) { db()->prepare('DELETE FROM editorial_board WHERE id=?')->execute([$bid]); $_SESSION['flash'] = 'Member removed.'; }
        redirect('/admin#board');
    }
    if ($action === 'toggle_board') {
        $bid = (int)($_POST['board_id'] ?? 0);
        if ($bid > 0) db()->prepare('UPDATE editorial_board SET active=1-active WHERE id=?')->execute([$bid]);
        redirect('/admin#board');
    }
}

$total_articles = (int)db()->query('SELECT COUNT(*) FROM manuscripts')->fetchColumn();
$total_volumes  = (int)db()->query('SELECT COUNT(DISTINCT volume) FROM manuscripts')->fetchColumn();
$total_board    = (int)db()->query('SELECT COUNT(*) FROM editorial_board WHERE active=1')->fetchColumn();
$articles       = db()->query('SELECT * FROM manuscripts ORDER BY year DESC, volume DESC, id DESC')->fetchAll();
$board          = db()->query('SELECT * FROM editorial_board ORDER BY display_order,id')->fetchAll();

$edit_board_id = isset($_GET['edit_board']) ? (int)$_GET['edit_board'] : 0;
$edit_member   = null;
if ($edit_board_id > 0) {
    $s = db()->prepare('SELECT * FROM editorial_board WHERE id = ?');
    $s->execute([$edit_board_id]);
    $edit_member = $s->fetch() ?: null;
}

$page_title = 'Admin Dashboard';
$page_css   = ['admin.css'];
include __DIR__ . '/../layout/header.php';
?>
<div class="page-banner">
    <div class="container" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
        <div>
            <h3 style="color:white;  "><i class="fas fa-gauge-high" style="color:white;  "></i> Admin Dashboard</h3>
            <p class="subtitle">Ambrose Alli University Journal of Medical Research &mdash; Content Management</p>
        </div>
        <a href="<?= SITE_URL ?>/admin-settings" class="btn btn-sm" style="margin-top:.5rem;background:white;">
            <i class="fas fa-sliders"></i> Site Settings
        </a>
    </div>
</div>
<section class="content-page">
  <div class="container">
    <?php if ($flash): ?><div class="alert alert-success"><?php echo e($flash); ?></div><?php endif; ?>
    <div class="admin-stats">
        <div class="admin-stat"><span class="admin-stat-num"><?php echo $total_articles; ?></span><span class="admin-stat-label"><i class="fas fa-file-alt"></i> Articles</span></div>
        <div class="admin-stat"><span class="admin-stat-num"><?php echo $total_volumes; ?></span><span class="admin-stat-label"><i class="fas fa-book"></i> Volumes</span></div>
        <div class="admin-stat"><span class="admin-stat-num"><?php echo $total_board; ?></span><span class="admin-stat-label"><i class="fas fa-users"></i> Board</span></div>
    </div>    <!-- Articles section -->
    <div class="admin-section">
      <div class="admin-section-header">
        <h2><i class="fas fa-file-alt"></i> Articles</h2>
        <a href="<?= SITE_URL ?>/upload-article" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Upload Article</a>
      </div>
      <?php if (empty($articles)): ?>
        <p class="text-muted">No articles yet.</p>
      <?php else: ?>
      <form method="POST" id="articles-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="delete_articles">
        <div class="table-wrapper">
          <table class="admin-table">
            <thead><tr><th><input type="checkbox" id="select-all"></th><th>Title</th><th>Authors</th><th>Vol.</th><th>Year</th><th>Category</th><th>Actions</th></tr></thead>
            <tbody>
              <?php foreach ($articles as $art): ?>
              <tr>
                <td><input type="checkbox" name="delete_ids[]" value="<?php echo $art['id']; ?>"></td>
                <td class="td-title" title="<?php echo e($art['title']); ?>"><?php echo e(mb_strimwidth($art['title'],0,55,'...')); ?></td>
                <td><?php echo e(mb_strimwidth($art['authors'],0,35,'...')); ?></td>
                <td><?php echo e($art['volume']); ?></td>
                <td><?php echo e($art['year']); ?></td>
                <td><span class="badge badge-gold"><?php echo e(mb_strimwidth($art['category'],0,20,'...')); ?></span></td>
                <td class="td-actions">
                  <a href="<?= SITE_URL ?>/article?id=<?php echo $art['id']; ?>" target="_blank" class="icon-btn" title="View"><i class="fas fa-eye"></i></a>
                  <?php if (!empty($art['pdf_upload'])): ?><a href="<?php echo e(SITE_URL . '/' . ltrim($art['pdf_upload'], '/')); ?>" target="_blank" class="icon-btn" title="PDF"><i class="fas fa-file-pdf"></i></a><?php endif; ?>
                  <a href="<?= SITE_URL ?>/edit-article?id=<?php echo $art['id']; ?>" class="icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="bulk-actions">
          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete selected? Cannot be undone.')"><i class="fas fa-trash"></i> Delete Selected</button>
        </div>
      </form>
      <?php endif; ?>
    </div>

    <!-- Board section -->
    <div class="admin-section" id="board">
      <div class="admin-section-header">
        <h2><i class="fas fa-users"></i> Editorial Board</h2>
      </div>
      <details class="board-add-form">
        <summary><i class="fas fa-plus"></i> Add Board Member</summary>
        <form method="POST" class="board-add-inner">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="add_board">
          <div class="form-row-3">
            <div class="form-group">
              <label class="required">Full Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="required">Role / Position</label>
              <input type="text" name="role" class="form-control" placeholder="e.g. Editor-in-Chief" required>
            </div>
            <div class="form-group">
              <label>Display Order</label>
              <input type="number" name="display_order" class="form-control" value="0" min="0">
            </div>
          </div>
          <div class="form-group">
            <label>Affiliation</label>
            <input type="text" name="affiliation" class="form-control" placeholder="Institution, Country">
          </div>
          <div class="form-actions-bar">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Add Member</button>
          </div>
        </form>
      </details>
      <?php if ($edit_member): ?>
      <div class="board-add-inner" style="background:var(--bg-alt);padding:1rem 1.5rem;border-top:1px solid var(--border)">
        <p style="font-weight:600;margin-bottom:.75rem"><i class="fas fa-edit"></i> Editing: <?php echo e($edit_member['name']); ?></p>
        <form method="POST">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="edit_board">
          <input type="hidden" name="board_id" value="<?php echo (int)$edit_member['id']; ?>">
          <div class="form-row-3">
            <div class="form-group">
              <label class="required">Full Name</label>
              <input type="text" name="name" class="form-control" required value="<?php echo e($edit_member['name']); ?>">
            </div>
            <div class="form-group">
              <label class="required">Role / Position</label>
              <input type="text" name="role" class="form-control" required value="<?php echo e($edit_member['role']); ?>">
            </div>
            <div class="form-group">
              <label>Display Order</label>
              <input type="number" name="display_order" class="form-control" min="0" value="<?php echo (int)$edit_member['display_order']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label>Affiliation</label>
            <input type="text" name="affiliation" class="form-control" value="<?php echo e($edit_member['affiliation']); ?>">
          </div>
          <div class="form-actions-bar">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>
            <a href="<?= SITE_URL ?>/admin#board" class="btn btn-outline btn-sm">Cancel</a>
          </div>
        </form>
      </div>
      <?php endif; ?>
      <?php if (empty($board)): ?>
        <p class="text-muted mt-sm" style="padding:1.25rem 1.5rem">No board members yet. Use the form above to add members.</p>
      <?php else: ?>
      <div class="table-wrapper">
        <table class="admin-table">
          <thead>
            <tr><th>Name</th><th>Role</th><th>Affiliation</th><th>Order</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($board as $m): ?>
            <tr class="<?php echo $m['active'] ? '' : 'row-inactive'; ?>">
              <td><?php echo e($m['name']); ?></td>
              <td><?php echo e($m['role']); ?></td>
              <td><?php echo e($m['affiliation']); ?></td>
              <td><?php echo (int)$m['display_order']; ?></td>
              <td>
                <span class="status-dot <?php echo $m['active'] ? 'active' : 'inactive'; ?>"></span>
                <?php echo $m['active'] ? 'Active' : 'Hidden'; ?>
              </td>
              <td class="td-actions">
                <a href="?edit_board=<?php echo (int)$m['id']; ?>#board" class="icon-btn" title="Edit"><i class="fas fa-edit"></i></a>
                <form method="POST" style="display:inline">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="toggle_board">
                  <input type="hidden" name="board_id" value="<?php echo (int)$m['id']; ?>">
                  <button type="submit" class="icon-btn" title="Toggle visibility">
                    <i class="fas fa-eye<?php echo $m['active'] ? '-slash' : ''; ?>"></i>
                  </button>
                </form>
                <form method="POST" style="display:inline" onsubmit="return confirm('Remove this member?')">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="delete_board">
                  <input type="hidden" name="board_id" value="<?php echo (int)$m['id']; ?>">
                  <button type="submit" class="icon-btn danger" title="Remove"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div><!-- /board -->

  </div>
</section>
<script>
const sa = document.getElementById('select-all');
if (sa) sa.addEventListener('change', () =>
  document.querySelectorAll('input[name="delete_ids[]"]').forEach(cb => cb.checked = sa.checked)
);
</script>
<?php include __DIR__ . '/../layout/footer.php'; ?>