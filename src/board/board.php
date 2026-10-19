
<?php
$page_title = 'Editorial Board';
$page_css   = ['board.css'];
require_once __DIR__ . '/../layout/header.php';

// Fetch active board members
$members = db()->query("
    SELECT *
    FROM editorial_board
    WHERE active = 1
    ORDER BY display_order ASC, id ASC
")->fetchAll();

// Group members by role
$grouped = [];
foreach ($members as $m) {
    $grouped[$m['role']][] = $m;
}

// Preferred role order
$role_order = [
    'Editor-in-Chief and Chairman',
    'Editor-in-Chief',
    'Deputy Editor-in-Chief',
    'Associate Editor-in-Chief',
    'Managing Editor',
    'Associate Editor',
    'Editorial Assistant',
    'Editorial Board Member',
    'Editorial Advisor',
    'Business Manager',
    'Internet Support',
];

uksort($grouped, function ($a, $b) use ($role_order) {
    $ia = array_search($a, $role_order, true);
    $ib = array_search($b, $role_order, true);

    $ia = ($ia === false) ? 999 : $ia;
    $ib = ($ib === false) ? 999 : $ib;

    return $ia <=> $ib;
});
?>

<!-- Banner -->
<div class="page-banner">
    <div class="container">
        <p class="subtitle">
            <i class="fas fa-users"></i> About the Journal
        </p>
        <h1>Editorial Board</h1>
    </div>
</div>

<section class="content-page">
    <div class="container">

        <?php if (empty($members)): ?>

            <p class="text-muted text-center">
                Editorial board information will be published shortly.
            </p>

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
                                    <?php
                                    $nameParts = preg_split(
                                        '/\s+/',
                                        trim($p['name'])
                                    );

                                    echo e(
                                        mb_strtoupper(
                                            mb_substr(
                                                $nameParts[1] ?? $nameParts[0] ?? '',
                                                0,
                                                1
                                            )
                                        )
                                    );
                                    ?>
                                </div>

                                <div class="board-info">

                                    <h3 class="board-name">
                                        <?= e($p['name']) ?>
                                    </h3>

                                    <?php if (!empty($p['department'])): ?>
                                        <p class="board-detail board-department">
                                            <i class="fas fa-building"></i>
                                            <strong>Department:</strong>
                                            <?= e($p['department']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($p['affiliation'])): ?>
                                        <p class="board-affiliation">
                                            <i class="fas fa-university"></i>
                                            <?= e($p['affiliation']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($p['email'])): ?>
                                        <p class="board-detail board-email">
                                            <i class="fas fa-envelope"></i>
                                            <strong>Email:</strong>
                                            <?php
                                            $emails = array_map(
                                                'trim',
                                                explode(';', $p['email'])
                                            );

                                            $emailLinks = [];

                                            foreach ($emails as $email) {
                                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                    $emailLinks[] =
                                                        '<a href="mailto:' .
                                                        e($email) .
                                                        '">' .
                                                        e($email) .
                                                        '</a>';
                                                }
                                            }

                                            echo implode('; ', $emailLinks);
                                            ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($p['telephone'])): ?>
                                        <p class="board-detail board-telephone">
                                            <i class="fas fa-phone"></i>
                                            <strong>Telephone:</strong>
                                            <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $p['telephone'])) ?>">
                                                <?= e($p['telephone']) ?>
                                            </a>
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