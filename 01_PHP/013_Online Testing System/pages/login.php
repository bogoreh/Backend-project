<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    redirect('../index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $user['user_type'];
        redirect('../index.php');
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Login to Your Account</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Login</button>
        </form>
        
        <p class="auth-link">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>