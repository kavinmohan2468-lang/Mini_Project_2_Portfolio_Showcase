<?php
require 'functions.php';
check('student');

$title = 'Student Dashboard';

$count = query(
    'SELECT COUNT(*) FROM projects WHERE user_id=?',
    [$_SESSION['id']]
)->fetchColumn();

require 'header.php';
?>

<h1>Student Dashboard</h1>

<p>
    Welcome, <?= e($_SESSION['name']) ?>.
    You have <?= $count ?> submission(s).
</p>

<section class="card">
    <h2>Available Assignments</h2>

    <label for="search">Search assignments</label>
    <input id="search" type="search" maxlength="200"
        placeholder="Type a title or description">

    <div id="results" aria-live="polite">Loading assignments...</div>
</section>

<?php require 'footer.php'; ?>