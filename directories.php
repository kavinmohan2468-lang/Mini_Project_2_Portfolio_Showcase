<?php
require 'functions.php';
check('admin');

$title = 'Uploaded Files';

$rows = query(
    'SELECT id,title,file_path FROM projects ORDER BY id DESC'
)->fetchAll();

require 'header.php';
?>

<h1>Uploaded Files</h1>
<p>Inspect the dedicated uploads folder.</p>

<section class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Stored Path</th>
                    <th>Size</th>
                    <th>File</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $row): ?>
                    <?php $path = __DIR__ . '/' . $row['file_path']; ?>

                    <tr>
                        <td><?= e($row['title']) ?></td>
                        <td><?= e($row['file_path']) ?></td>
                        <td>
                            <?= is_file($path)
                                ? round(filesize($path) / 1024, 1) . ' KB'
                                : 'Missing' ?>
                        </td>
                        <td>
                            <a href="file.php?id=<?= $row['id'] ?>">Download</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$rows): ?>
                    <tr><td colspan="4">No uploaded files.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require 'footer.php'; ?>