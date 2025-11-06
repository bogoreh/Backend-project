<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../models/Ad.php';

session_start();

// Simple admin authentication (in real app, use proper authentication)
if (isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    
    // Simple hardcoded credentials for demo
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['admin'] = true;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    redirect('admin.php');
}

// Check if admin is logged in
$isAdmin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ad Dispenser</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Ad Dispenser Admin Panel</h1>
            <?php if ($isAdmin): ?>
                <nav>
                    <a href="admin.php?page=dashboard">Dashboard</a>
                    <a href="admin.php?page=create">Create Ad</a>
                    <a href="admin.php?page=list">All Ads</a>
                    <a href="admin.php?action=logout">Logout</a>
                </nav>
            <?php endif; ?>
        </header>
        
        <main>
            <?php if (!$isAdmin): ?>
                <!-- Login Form -->
                <div class="login-form">
                    <h2>Admin Login</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit" name="login">Login</button>
                    </form>
                    <p>Demo credentials: admin / password</p>
                </div>
            <?php else: ?>
                <!-- Admin Content -->
                <?php
                $page = $_GET['page'] ?? 'dashboard';
                $adModel = new Ad();
                
                switch ($page) {
                    case 'dashboard':
                        include '../views/admin/dashboard.php';
                        break;
                    case 'create':
                        include '../views/admin/create_ad.php';
                        break;
                    case 'list':
                        include '../views/admin/list_ads.php';
                        break;
                    default:
                        include '../views/admin/dashboard.php';
                }
                ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>