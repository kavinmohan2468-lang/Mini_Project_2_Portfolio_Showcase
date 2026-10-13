<?php
if (!isset($rows)) {
    http_response_code(404);
    exit;
}
?>

<h1><?= e($title) ?></h1>
<p>Total: <?= count($rows) ?> submission(s)</p>

<section class="card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Assignment / Project</th>
                    <th>Category</th>
                    <th>Technology</th>
                    <th>File</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td>
                            <?= e($row['full_name']) ?><br>
                            <small><?= e($row['email']) ?></small>
                        </td>

                        <td>
                            <?= e($row['assignment']) ?><br>
                            <strong><?= e($row['title']) ?></strong>

                            <details>
                                <summary>Description</summary>
                                <p class="description"><?= e($row['description']) ?></p>
                            </details>
                        </td>

                        <td><?= e($row['category_name']) ?></td>
                        <td><?= e($row['tech_stack']) ?></td>

                        <td>
                            <a href="file.php?id=<?= $row['id'] ?>&view=1"
                                target="_blank" rel="noopener">View</a><br>

                            <a href="file.php?id=<?= $row['id'] ?>">Download</a>
                        </td>

                        <td><?= e($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$rows): ?>
                    <tr><td colspan="6">No submissions yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
