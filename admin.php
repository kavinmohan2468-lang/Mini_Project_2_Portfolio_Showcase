<?php
require 'functions.php';
check('admin');

$title = 'Admin Dashboard';

$counts = [
    'Students' => query(
        'SELECT COUNT(*) FROM users WHERE role=?',
        ['student']
    )->fetchColumn(),
    'Categories' => query('SELECT COUNT(*) FROM categories')->fetchColumn(),
    'Assignments' => query('SELECT COUNT(*) FROM assignments')->fetchColumn(),
    'Submissions' => query('SELECT COUNT(*) FROM projects')->fetchColumn()
];

$rows = query(
    'SELECT p.title,p.created_at,u.full_name
     FROM projects p
     JOIN users u ON p.user_id=u.id
     ORDER BY p.id DESC LIMIT 5'
)->fetchAll();

require 'header.php';
?>

<h1>Admin Overview</h1>
<p>Manage student assignments and portfolio submissions.</p>

<div class="row g-3 mb-4">
    <?php foreach ($counts as $label => $number): ?>
        <div class="col-6 col-lg-3">
            <div class="card stat">
                <span><?= $label ?></span>
                <strong><?= $number ?></strong>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<section class="card">
    <h2>Recent Submissions</h2>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Project</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e($row['full_name']) ?></td>
                        <td><?= e($row['title']) ?></td>
                        <td><?= e($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$rows): ?>
                    <tr><td colspan="3">No submissions yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require 'footer.php'; ?>