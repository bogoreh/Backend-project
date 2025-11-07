<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

if (isset($_GET['logout'])) {
    logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Simple CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="admin" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" value="admin123" required>
            </div>
            
            <div class="form-actions">
                <button type="submit">Login</button>
            </div>
        </form>
        
        <div class="login-note">
            <p><strong>Default credentials:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin123</code></p>
        </div>
    </div>
</body>
</html>