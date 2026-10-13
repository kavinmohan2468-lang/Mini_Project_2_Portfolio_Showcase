
<?php
require 'functions.php';
require 'upload.php';

check('student');

$title = 'Submit Assignment';
$error = '';

$selected = input('assignment_id') ?:
    (is_string($_GET['id'] ?? null) ? $_GET['id'] : '');

if (post()) {
    $aid = filter_var(input('assignment_id'), FILTER_VALIDATE_INT);
    $cid = filter_var(input('category_id'), FILTER_VALIDATE_INT);
    $name = trim((string) input('title'));
    $description = trim((string) input('description'));
    $tech = trim((string) input('tech_stack'));
    $path = '';

    try {
        // Validate form fields
        if (
            !$aid || !$cid ||
            !valid($name, 200) ||
            !valid($description, 5000) ||
            !valid($tech, 255)
        ) {
            throw new Exception(
                'Please complete all fields correctly.'
            );
        }

        // Check selected assignment
        $assignment = query(
            'SELECT id FROM assignments WHERE id = ?',
            [$aid]
        )->fetch();

        // Check selected category
        $category = query(
            'SELECT id FROM categories WHERE id = ?',
            [$cid]
        )->fetch();

        if (!$assignment || !$category) {
            throw new Exception(
                'Please choose a valid assignment and category.'
            );
        }

        // Validate and upload the file
        $path = uploadFile();

        if (!$path) {
            throw new Exception(
                'File upload failed. Please choose a valid file.'
            );
        }

        // Get the original uploaded filename
        $originalName = basename(
            (string) ($_FILES['file']['name'] ?? '')
        );

        if ($originalName === '') {
            throw new Exception(
                'Could not retrieve the uploaded filename.'
            );
        }

        // Save submission to database
        query(
            'INSERT INTO projects
             (user_id, category_id, assignment_id, title,
              description, tech_stack, file_path, original_name)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $_SESSION['id'],
                $cid,
                $aid,
                $name,
                $description,
                $tech,
                $path,
                $originalName
            ]
        );

        done(
            'Assignment submitted successfully.',
            'my_submissions.php'
        );

    } catch (Throwable $e) {
        // Remove uploaded file if the database save fails
        if ($path && is_file(__DIR__ . '/' . $path)) {
            unlink(__DIR__ . '/' . $path);
        }

        // Log technical details for debugging
        error_log('Submit Assignment Error: ' . $e->getMessage());

        // Show validation errors, but not raw database errors
        $error = $e instanceof PDOException
            ? 'Submission could not be saved. Please check the server error log.'
            : $e->getMessage();
    }
}

$assignments = query(
    'SELECT id, title FROM assignments ORDER BY id DESC'
)->fetchAll();

$categories = query(
    'SELECT id, category_name FROM categories ORDER BY category_name'
)->fetchAll();

require 'header.php';
?>

<h1>Submit Assignment</h1>

<section class="card">

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!$assignments || !$categories): ?>

        <p>
            The administrator must create an assignment and category first.
        </p>

    <?php else: ?>

        <form method="post"
              enctype="multipart/form-data"
              id="submitForm">

            <?php token(); ?>

            <label for="assignment_id">Assignment</label>
            <select id="assignment_id"
                    name="assignment_id"
                    required>
                <option value="">Choose assignment</option>

                <?php foreach ($assignments as $row): ?>
                    <option
                        value="<?= e($row['id']) ?>"
                        <?= (string)$selected === (string)$row['id']
                            ? 'selected' : '' ?>>
                        <?= e($row['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="title">Project Title</label>
            <input id="title"
                   name="title"
                   maxlength="200"
                   value="<?= e(input('title')) ?>"
                   required>

            <label for="category_id">Category</label>
            <select id="category_id"
                    name="category_id"
                    required>
                <option value="">Choose category</option>

                <?php foreach ($categories as $row): ?>
                    <option
                        value="<?= e($row['id']) ?>"
                        <?= input('category_id') == $row['id']
                            ? 'selected' : '' ?>>
                        <?= e($row['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="description">Project Description</label>
            <textarea id="description"
                      name="description"
                      maxlength="5000"
                      required><?= e(input('description')) ?></textarea>

            <label for="tech_stack">Technology Used</label>
            <input id="tech_stack"
                   name="tech_stack"
                   maxlength="255"
                   placeholder="PHP, MySQL, JavaScript"
                   value="<?= e(input('tech_stack')) ?>"
                   required>

            <label for="file">
                Assignment File (PDF, DOCX or TXT, maximum 5 MB)
            </label>

            <input id="file"
                   name="file"
                   type="file"
                   accept=".pdf,.docx,.txt"
                   required>

            <button type="submit" class="btn btn-primary">
                Submit Assignment
            </button>

        </form>

        <script>
        document.getElementById('submitForm')
            .addEventListener('submit', function (event) {
                const input = document.getElementById('file');
                const file = input.files[0];

                if (!file) {
                    event.preventDefault();
                    alert('Please select a file.');
                    return;
                }

                const allowed = ['pdf', 'docx', 'txt'];
                const extension = file.name
                    .split('.')
                    .pop()
                    .toLowerCase();

                if (!allowed.includes(extension)) {
                    event.preventDefault();
                    alert('Only PDF, DOCX and TXT files are allowed.');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    event.preventDefault();
                    alert('File size must not exceed 5 MB.');
                }
            });
        </script>

    <?php endif; ?>

</section>

<?php require 'footer.php'; ?>

                