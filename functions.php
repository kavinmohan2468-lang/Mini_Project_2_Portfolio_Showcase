<?php
require_once 'db.php';

ini_set('session.use_strict_mode', '1');

session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax'
]);

session_start();

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

$_SESSION['token'] = $_SESSION['token'] ?? bin2hex(random_bytes(32));

function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function input($name) {
    return is_string($_POST[$name] ?? null) ? trim($_POST[$name]) : '';
}

function valid($text, $max) {
    return $text !== '' && strlen($text) <= $max;
}

function go($page) {
    header('Location: ' . $page);
    exit;
}

function home() {
    return ($_SESSION['role'] ?? '') === 'admin'
        ? 'admin.php' : 'student.php';
}

function check($role = '') {
    if (empty($_SESSION['id']) ||
        time() - ($_SESSION['last'] ?? 0) > 1800) {
        unset($_SESSION['id'], $_SESSION['role']);
        $_SESSION['message'] = 'Please log in to continue.';
        go('login.php');
    }

    $_SESSION['last'] = time();

    if ($role && $_SESSION['role'] !== $role) {
        go(home());
    }
}

function token() {
    echo '<input type="hidden" name="token" value="' .
        e($_SESSION['token']) . '">';
}

function post() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }

    if (!hash_equals($_SESSION['token'], input('token'))) {
        http_response_code(403);
        exit('Invalid form token. Reload the page and try again.');
    }

    return true;
}

function done($message, $page) {
    $_SESSION['message'] = $message;
    go($page);
}