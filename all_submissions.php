<?php
require 'functions.php';
check('admin');

$title = 'All Student Submissions';

$rows = query(
    'SELECT p.*,a.title AS assignment,c.category_name,u.full_name,u.email
     FROM projects p
     JOIN assignments a ON p.assignment_id=a.id
     JOIN categories c ON p.category_id=c.id
     JOIN users u ON p.user_id=u.id
     ORDER BY p.id DESC'
)->fetchAll();

require 'header.php';
require 'submission_table.php';
require 'footer.php';