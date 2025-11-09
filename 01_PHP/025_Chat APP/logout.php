<?php
session_start();
include 'config/database.php';

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("DELETE FROM online_users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

session_destroy();
header('Location: login.php');
exit;
?>