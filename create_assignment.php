<?php
require 'functions.php';
check('admin');

$title = 'Create Assignment';

if (post()) {
    $name = input('title');
    $description = input('description');

    if (!valid($name, 200) || !valid($description, 5000)) {
        $error = 'Enter a title and description within the limits.';
    } else {
        query(
            'INSERT INTO assignments (title,description) VALUES (?,?)',
            [$name, $description]
        );

        done('Assignment created.', 'create_assignment.php');
    }
}

$rows = query('SELECT * FROM assignments ORDER BY id DESC')->fetchAll();

require 'header.php';
?>

<h1>Create Assignment</h1>

<section class="card">
    <form method="post">
        <?php token(); ?>

        <label for="title">Assignment Title</label>
        <input id="title" name="title" maxlength="200"
            value="<?= e(input('title')) ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description"
            maxlength="5000" required><?= e(input('description')) ?></textarea>

        <button class="btn btn-primary">Create Assignment</button>
    </form>
</section>

<section class="card mt-4">
    <h2>Existing Assignments</h2>

    <?php if (!$rows): ?>
        <p>No assignments yet.</p>
    <?php endif; ?>

    <?php foreach ($rows as $row): ?>
        <article class="assignment">
            <h3><?= e($row['title']) ?></h3>
            <p class="description"><?= e($row['description']) ?></p>
        </article>
    <?php endforeach; ?>
</section>

<?php require 'footer.php'; ?>