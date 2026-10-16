<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) redirect('/admin');

$stmt = db()->prepare('SELECT * FROM manuscripts WHERE id = ?');
$stmt->execute([$id]);
$a = $stmt->fetch();
if (!$a) redirect('/admin');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Invalid form submission.';
    } else {
        $title    = trim($_POST['title']    ?? '');
        $authors  = trim($_POST['authors']  ?? '');
        $abstract = trim($_POST['abstract'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $year     = (int)($_POST['year']    ?? 0);
        $volume   = (int)($_POST['volume']  ?? 0);
        $issue    = trim($_POST['issue']    ?? '');
        $pages    = trim($_POST['pages']    ?? '');
        $category = trim($_POST['category'] ?? '');

        if (empty($title))   $errors[] = 'Title is required.';
        if (empty($authors)) $errors[] = 'Authors are required.';
        if ($year < 2000)    $errors[] = 'Valid year is required.';
        if ($volume < 1)     $errors[] = 'Valid volume is required.';
        if (empty($issue))   $errors[] = 'Issue is required.';
        if (empty($pages))   $errors[] = 'Pages are required.';
        if (!in_array($category, CATEGORIES, true)) $errors[] = 'Please select a category.';

        $pdf_path = $a['pdf_upload'];
        $pdf      = $_FILES['pdf_upload'] ?? [];

        if (!empty($pdf['tmp_name']) && $pdf['error'] === 0) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $pdf['tmp_name']);
            finfo_close($finfo);
            if ($mime !== 'application/pdf') {
                $errors[] = 'Only PDF files are accepted.';
            } else {
                $upload_dir = dirname(__DIR__, 2) . '/uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                $basename = preg_replace('/[^a-zA-Z0-9_.-]/', '-', pathinfo($pdf['name'], PATHINFO_FILENAME));
                $filename = $basename . '_' . uniqid() . '.pdf';
                if (move_uploaded_file($pdf['tmp_name'], $upload_dir . $filename)) {
                    if (!empty($a['pdf_upload'])) {
                        $old = dirname(__DIR__, 2) . '/' . $a['pdf_upload'];
                        if (file_exists($old)) @unlink($old);
                    }
                    $pdf_path = 'uploads/' . $filename;
                } else {
                    $errors[] = 'Failed to save file.';
                }
            }
        }

        if (empty($errors)) {
            db()->prepare('UPDATE manuscripts SET title=?,authors=?,abstract=?,email=?,year=?,volume=?,issue=?,pages=?,category=?,pdf_upload=? WHERE id=?')
               ->execute([$title,$authors,$abstract,$email,$year,$volume,$issue,$pages,$category,$pdf_path,$id]);
            $_SESSION['flash'] = 'Article updated!';
            redirect('/admin');
        }
    }
}
$page_title = 'Edit Article';
$page_css   = ['admin.css', 'upload-article.css'];
include __DIR__ . '/../layout/header.php';
?>
<div class="page-banner">
    <div class="container">
        <p class="subtitle"><a href="<?= SITE_URL ?>/admin" style="color:rgba(255,255,255,.7)"><i class="fas fa-arrow-left"></i> Dashboard</a></p>
        <h1>Edit Article</h1>
    </div>
</div>
<section class="content-page">
    <div class="container" style="max-width:820px">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><ul><?php foreach($errors as $e) echo '<li>'.e($e).'</li>'; ?></ul></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <?php echo csrf_field(); ?>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="required">Title</label>
                    <input type="text" name="title" class="form-control" required value="<?php echo e($a['title']); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Author(s)</label>
                    <input type="text" name="authors" class="form-control" required value="<?php echo e($a['authors']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Abstract</label>
                <textarea name="abstract" class="form-control" rows="5"><?php echo e($a['abstract']); ?></textarea>
            </div>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="required">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php foreach (CATEGORIES as $cat): ?>
                            <option value="<?php echo e($cat); ?>" <?php echo ($a['category'] === $cat) ? 'selected' : ''; ?>><?php echo e($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Corresponding Author Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($a['email'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row-3">
                <div class="form-group">
                    <label class="required">Year</label>
                    <input type="number" name="year" class="form-control" min="2000" required value="<?php echo e($a['year']); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Volume</label>
                    <input type="number" name="volume" class="form-control" min="1" required value="<?php echo e($a['volume']); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Issue</label>
                    <input type="text" name="issue" class="form-control" required value="<?php echo e($a['issue']); ?>">
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="required">Pages</label>
                    <input type="text" name="pages" class="form-control" required value="<?php echo e($a['pages']); ?>">
                </div>
                <div class="form-group">
                    <label>Replace PDF (optional)</label>
                    <input type="file" name="pdf_upload" class="form-control" accept="application/pdf">
                    <?php if (!empty($a['pdf_upload'])): ?>
                        <span class="text-muted" style="font-size:.8rem">Current: <?php echo e(basename($a['pdf_upload'])); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-actions-bar">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                <a href="<?= SITE_URL ?>/admin" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>