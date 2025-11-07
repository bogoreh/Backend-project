<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = $_POST['content'];
    $excerpt = sanitizeInput($_POST['excerpt']);
    $status = $_POST['status'];
    $author_id = $_SESSION['user_id'];
    
    if (createPost($title, $content, $excerpt, $author_id, $status)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Error creating post!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Add New Post</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label>Title:</label>
        <input type="text" name="title" required>
    </div>
    
    <div class="form-group">
        <label>Excerpt:</label>
        <textarea name="excerpt" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label>Content:</label>
        <textarea name="content" rows="10" required></textarea>
    </div>
    
    <div class="form-group">
        <label>Status:</label>
        <select name="status">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
    </div>
    
    <button type="submit">Create Post</button>
    <a href="dashboard.php" class="btn-cancel">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>