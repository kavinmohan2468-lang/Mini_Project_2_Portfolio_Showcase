<?php
require 'functions.php';

if (!post()) {
    go('index.php');
}

$_SESSION = [];

setcookie(session_name(), '', [
    'expires' => time() - 3600,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_destroy();
go('login.php');