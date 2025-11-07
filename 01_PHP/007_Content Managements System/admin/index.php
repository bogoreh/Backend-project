<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

// Redirect to login if not authenticated, otherwise to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
?>