<?php
require 'functions.php';
check('admin');

$title = 'Categories';

if (post()) {
    $name = input('category_name');
    $description = input('description');

    if (!valid($name, 100) || !valid($description, 5000)) {
        $error = 'Enter a category name and description within the limits.';
    } else {
        try {
            query(
                'INSERT INTO categories (category_name,description) VALUES (?,?)',
                [$name, $description]
            );

            done('Category added.', 'categories.php');
        } catch (PDOException $e) {
            $error = $e->getCode() == '23000'
                ? 'Category already exists.'
                : 'Unable to add category.';
        }
    }
}

$rows = query(
    'SELECT * FROM categories ORDER BY category_name'
)->fetchAll();

require 'header.php';
?>

<h1>Categories</h1>

<div class="row g-4">
    <div class="col-md-5">
        <section class="card">
            <h2>Create Category</h2>

            <form method="post">
                <?php token(); ?>

                <label for="category_name">Category Name</label>
                <input id="category_name" name="category_name"
                    maxlength="100"
                    value="<?= e(input('category_name')) ?>" required>

                <label for="description">Description</label>
                <textarea id="description" name="description"
                    maxlength="5000" required><?= e(input('description')) ?></textarea>

                <button class="btn btn-primary">Add Category</button>
            </form>
        </section>
    </div>

    <div class="col-md-7">
        <section class="card">
            <h2>Existing Categories</h2>

            <?php foreach ($rows as $row): ?>
                <article class="assignment">
                    <h3><?= e($row['category_name']) ?></h3>
                    <p><?= e($row['description']) ?></p>
                </article>
            <?php endforeach; ?>

            <?php if (!$rows): ?>
                <p>No categories yet.</p>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require 'footer.php'; ?>