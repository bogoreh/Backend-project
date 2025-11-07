<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAuth();

if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    
    // Verify the post exists and belongs to the current user (optional security)
    $post = getPost($post_id);
    
    if ($post) {
        if (deletePost($post_id)) {
            $_SESSION['message'] = "Post deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting post!";
        }
    } else {
        $_SESSION['error'] = "Post not found!";
    }
}

header('Location: dashboard.php');
exit;
?>