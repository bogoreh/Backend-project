<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    deletePost($_POST['delete_id']);
    $_SESSION['message'] = "Post deleted successfully!";
    header('Location: dashboard.php');
    exit;
}

$posts = getPosts(null, 'published');
$drafts = getPosts(null, 'draft');

// Get stats
$total_posts = count($posts) + count($drafts);
$published_posts = count($posts);
$draft_posts = count($drafts);
?>

<?php include '../includes/header.php'; ?>

<h2>Dashboard</h2>

<?php if (isset($_SESSION['message'])): ?>
    <div class="success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<div class="stats">
    <div class="stat-card">
        <span class="stat-number"><?php echo $total_posts; ?></span>
        <span class="stat-label">Total Posts</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $published_posts; ?></span>
        <span class="stat-label">Published</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $draft_posts; ?></span>
        <span class="stat-label">Drafts</span>
    </div>
</div>

<div class="dashboard-actions">
    <a href="add_post.php" class="btn">Add New Post</a>
</div>

<h3>Published Posts</h3>
<?php if ($posts): ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post['title']); ?></td>
                <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                <td class="post-actions">
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn">Edit</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')" class="btn-cancel">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No published posts. <a href="add_post.php">Create your first post!</a></p>
<?php endif; ?>

<h3>Drafts</h3>
<?php if ($drafts): ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drafts as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post['title']); ?></td>
                <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                <td class="post-actions">
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No drafts.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>