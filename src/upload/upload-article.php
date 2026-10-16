<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_admin();

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
        $pdf      = $_FILES['pdf_upload']   ?? [];

        if (empty($title))    $errors[] = 'Title is required.';
        if (empty($authors))  $errors[] = 'Authors are required.';
        if ($year < 2000)     $errors[] = 'Valid year is required.';
        if ($volume < 1)      $errors[] = 'Valid volume is required.';
        if (empty($issue))    $errors[] = 'Issue is required.';
        if (empty($pages))    $errors[] = 'Pages are required.';
        if (!in_array($category, CATEGORIES, true)) $errors[] = 'Please select a category.';
        if (empty($pdf['tmp_name']) || $pdf['error'] !== 0) $errors[] = 'PDF file is required.';

        if (empty($errors)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $pdf['tmp_name']);
            finfo_close($finfo);
            if ($mime !== 'application/pdf') {
                $errors[] = 'Only PDF files are accepted.';
            }
        }

        if (empty($errors)) {
            $upload_dir = dirname(__DIR__, 2) . '/uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            // Parse page range for filename
            $page_start = $page_end = '';
            if (preg_match('/^(\d+)[^\d]+(\d+)$/', $pages, $m)) {
                $page_start = str_pad($m[1], 3, '0', STR_PAD_LEFT);
                $page_end   = str_pad($m[2], 3, '0', STR_PAD_LEFT);
            } elseif (preg_match('/^(\d+)$/', $pages, $m)) {
                $page_start = $page_end = str_pad($m[1], 3, '0', STR_PAD_LEFT);
            } else {
                // fallback: remove non-digits, use as-is
                $page_start = $page_end = preg_replace('/\D/', '', $pages);
            }

            // Clean issue for filename (remove spaces, slashes, etc)
            $issue_clean = preg_replace('/[^a-zA-Z0-9-]/', '', $issue);

            // Build new filename
            $filename = sprintf(
                'aaujmr.%d.v%sn%s.%s-%s.pdf',
                $year,
                $volume,
                $issue_clean ? 'n' . $issue_clean : '',
                $page_start,
                $page_end
            );

            // Ensure unique filename if already exists
            $final_path = $upload_dir . $filename;
            $counter = 2;
            while (file_exists($final_path)) {
                $filename = sprintf(
                    'aaujmr.%d.v%sn%s.%s-%s-%d.pdf',
                    $year,
                    $volume,
                    $issue_clean ? 'n' . $issue_clean : '',
                    $page_start,
                    $page_end,
                    $counter++
                );
                $final_path = $upload_dir . $filename;
            }

            if (!move_uploaded_file($pdf['tmp_name'], $final_path)) {
                $errors[] = 'Failed to save file.';
            } else {
                db()->prepare('INSERT INTO manuscripts (title,authors,abstract,email,year,volume,issue,pages,category,pdf_upload) VALUES (?,?,?,?,?,?,?,?,?,?)')
                    ->execute([$title,$authors,$abstract,$email,$year,$volume,$issue,$pages,$category,'uploads/'.$filename]);
                $_SESSION['flash'] = 'Article uploaded successfully!';
                redirect('/admin');
            }
        }
    }
}
$page_title = 'Upload Article';
$page_css   = ['admin.css'];
include __DIR__ . '/../layout/header.php';
?>
<div class="page-banner">
    <div class="container">
        <p class="subtitle"><a href="<?= SITE_URL ?>/admin" style="color:rgba(255,255,255,.7)"><i class="fas fa-arrow-left"></i> Dashboard</a></p>
        <h1>Upload New Article</h1>
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
                    <input type="text" name="title" class="form-control" required value="<?php echo e($_POST['title'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Author(s)</label>
                    <input type="text" name="authors" class="form-control" required value="<?php echo e($_POST['authors'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Abstract</label>
                <textarea name="abstract" class="form-control" rows="5"><?php echo e($_POST['abstract'] ?? ''); ?></textarea>
            </div>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="required">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php foreach (CATEGORIES as $cat): ?>
                            <option value="<?php echo e($cat); ?>" <?php echo (($_POST['category'] ?? '') === $cat) ? 'selected' : ''; ?>><?php echo e($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Corresponding Author Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($_POST['email'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row-3">
                <div class="form-group">
                    <label class="required">Year</label>
                    <input type="number" name="year" class="form-control" min="2000" max="<?php echo date('Y')+1; ?>" required value="<?php echo e((string)($_POST['year'] ?? '')); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Volume</label>
                    <input type="number" name="volume" class="form-control" min="1" required value="<?php echo e((string)($_POST['volume'] ?? '')); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Issue</label>
                    <input type="text" name="issue" class="form-control" required value="<?php echo e($_POST['issue'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-group">
                    <label class="required">Pages (e.g. 1-8)</label>
                    <input type="text" name="pages" class="form-control" required value="<?php echo e($_POST['pages'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">PDF File</label>
                    <input type="file" name="pdf_upload" class="form-control" accept="application/pdf" required>
                </div>
            </div>
            <div class="form-actions-bar">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Article</button>
                <a href="<?= SITE_URL ?>/admin" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>