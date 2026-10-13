<?php
require 'functions.php';
check();

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

$row = $id ? query(
    'SELECT user_id,file_path FROM projects WHERE id=?',
    [$id]
)->fetch() : false;

if (!$row ||
    ($_SESSION['role'] !== 'admin' && $row['user_id'] != $_SESSION['id'])) {
    http_response_code(404);
    exit('File not found.');
}

$root = realpath(__DIR__ . '/uploads');
$path = realpath(__DIR__ . '/' . $row['file_path']);

if (!$root || !$path || dirname($path) !== $root || !is_file($path)) {
    http_response_code(404);
    exit('File not found.');
}

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

$types = [
    'pdf' => 'application/pdf',
    'txt' => 'text/plain; charset=UTF-8',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

if (!isset($types[$ext])) {
    http_response_code(403);
    exit('Unsupported file.');
}

$mode = isset($_GET['view']) && $ext !== 'docx'
    ? 'inline' : 'attachment';

header('Content-Security-Policy: sandbox');
header('Content-Type: ' . $types[$ext]);
header('Content-Disposition: ' . $mode .
    '; filename="submission-' . $id . '.' . $ext . '"');
header('Content-Length: ' . filesize($path));

readfile($path);
exit;