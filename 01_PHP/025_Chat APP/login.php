<?php
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Update online status
        $stmt = $pdo->prepare("REPLACE INTO online_users (user_id) VALUES (?)");
        $stmt->execute([$user['id']]);
        
        header('Location: chat.php');
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="auth-container">
    <div class="auth-form">
        <h2>Login to Chat</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>