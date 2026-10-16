<?php
$page_title = 'Editorial Board';
$page_css   = ['board.css'];
require_once __DIR__ . '/../layout/header.php';

// Fetch all active board members, ordered by display_order then name
$members = db()->query("
    SELECT * FROM editorial_board
    WHERE active = 1
    ORDER BY display_order ASC, id ASC
")->fetchAll();

// Group by role for display
$grouped = [];
foreach ($members as $m) {
    $grouped[$m['role']][] = $m;
}

// Define preferred role order
$role_order = [
    'Editor-in-Chief',
    'Associate Editor-in-Chief',
    'Managing Editor',
    'Associate Editor',
    'Editorial Board Member',
    'Editorial Advisor',
    'Business Manager',
];
uksort($grouped, function ($a, $b) use ($role_order) {
    $ia = array_search($a, $role_order);
    $ib = array_search($b, $role_order);
    $ia = $ia === false ? 999 : $ia;
    $ib = $ib === false ? 999 : $ib;
    return $ia <=> $ib;
});
?>

<!-- Banner -->
<div class="page-banner">
    <div class="container">
        <p class="subtitle"><i class="fas fa-users"></i> About the Journal</p>
        <h1>Editorial Board</h1>
    </div>
</div>

<section class="content-page">
    <div class="container">
        <?php if (empty($members)): ?>
            <p class="text-muted text-center">Editorial board information will be published shortly.</p>
        <?php else: ?>
            <?php foreach ($grouped as $role => $people): ?>
            <div class="board-group">
                <h2 class="board-role-heading">
                    <span class="board-role-bar"></span>
                    <?= e($role) ?>
                </h2>
                <div class="board-cards">
                    <?php foreach ($people as $p): ?>
                    <div class="board-card">
                        <div class="board-avatar" aria-hidden="true">
                            <?= mb_strtoupper(mb_substr(explode(' ', trim($p['name']))[1] ?? $p['name'], 0, 1)) ?>
                        </div>
                        <div class="board-info">
                            <h3 class="board-name"><?= e($p['name']) ?></h3>
                            <?php if (!empty($p['affiliation'])): ?>
                                <p class="board-affiliation">
                                    <i class="fas fa-university"></i>
                                    <?= e($p['affiliation']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
