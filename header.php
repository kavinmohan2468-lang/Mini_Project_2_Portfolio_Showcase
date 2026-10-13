<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Portfolio Showcase') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="script.js" defer></script>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex flex-wrap gap-3">
        <a class="navbar-brand" href="index.php">Portfolio Showcase</a>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <?php if (!empty($_SESSION['id'])): ?>

                <a href="<?= home() ?>">Dashboard</a>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="create_assignment.php">Create Assignment</a>
                    <a href="categories.php">Categories</a>
                    <a href="all_submissions.php">All Submissions</a>
                    <a href="directories.php">Files</a>
                <?php else: ?>
                    <a href="submit.php">Submit Assignment</a>
                    <a href="my_submissions.php">My Submissions</a>
                <?php endif; ?>

                <form action="logout.php" method="post" class="m-0">
                    <?php token(); ?>
                    <button class="btn btn-sm btn-outline-light">Logout</button>
                </form>

            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4">

<?php if (!empty($_SESSION['message'])): ?>
    <div class="alert alert-success" role="status">
        <?= e($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?= e($error) ?>
    </div>
<?php endif; ?>