<?php
session_start();
require_once 'config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function generateAccountNumber() {
    return 'ACC' . date('Ymd') . rand(1000, 9999);
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>