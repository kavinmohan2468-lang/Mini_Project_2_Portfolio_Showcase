<?php
require 'functions.php';
check('student');

$q = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';
$q = '%' . substr($q, 0, 200) . '%';

$rows = query(
    'SELECT * FROM assignments
     WHERE title LIKE ? OR description LIKE ?
     ORDER BY id DESC',
    [$q, $q]
)->fetchAll();

if (!$rows) {
    echo '<p>No assignments found.</p>';
}

foreach ($rows as $row):
?>

<article class="assignment">
    <h3><?= e($row['title']) ?></h3>
    <p class="description"><?= e($row['description']) ?></p>

    <a class="btn btn-primary btn-sm"
        href="submit.php?id=<?= $row['id'] ?>">
        Submit Assignment
    </a>
</article>

<?php endforeach; ?>