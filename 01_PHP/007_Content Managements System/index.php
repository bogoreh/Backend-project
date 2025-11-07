<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
?>

<?php include 'includes/header.php'; ?>

<h2>Latest Posts</h2>

<?php
$posts = getPosts();
if ($posts):
    foreach ($posts as $post):
?>
    <article class="post">
        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
        <p class="meta">
            Posted by <?php echo htmlspecialchars($post['username']); ?> 
            on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
        </p>
        <div class="excerpt">
            <?php echo nl2br(htmlspecialchars($post['excerpt'])); ?>
        </div>
        <a href="post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More</a>
    </article>
    <hr>
<?php
    endforeach;
else:
?>
    <p>No posts found.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>