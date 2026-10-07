<?php
require_once __DIR__ . '/functions.php';

function require_login() {
    if (empty($_SESSION['user'])) {
        redirect('login.php?message=' . urlencode('Please login to continue.'));
    }
}

function require_admin() {
    if (empty($_SESSION['admin'])) {
        redirect('login.php');
    }
}
?>