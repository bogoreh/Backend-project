<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAuth();

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$post_id = intval($_GET['id']);
$post = getPost($post_id);

if (!$post) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = $_POST['content'];
    $excerpt = sanitizeInput($_POST['excerpt']);
    $status = $_POST['status'];
    
    if (updatePost($post_id, $title, $content, $excerpt, $status)) {
        $_SESSION['message'] = "Post updated successfully!";
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Error updating post!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Edit Post</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
    </div>
    
    <div class="form-group">
        <label>Excerpt:</label>
        <textarea name="excerpt" rows="3"><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label>Content:</label>
        <textarea name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label>Status:</label>
        <select name="status">
            <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
            <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
        </select>
    </div>
    
    <button type="submit">Update Post</button>
    <a href="dashboard.php" class="btn-cancel">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>