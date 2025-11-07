<?php
session_start();
require_once 'config/database.php';

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}
?>