<?php
require 'functions.php';

if (empty($_SESSION['id'])) {
    go('login.php');
}

check();
go(home());