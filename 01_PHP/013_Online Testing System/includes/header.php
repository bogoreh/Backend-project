<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Testing System</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-graduation-cap"></i>
                Online Testing System
            </div>
            <div class="nav-links">
                <?php if (isLoggedIn()): ?>
                    <a href="index.php">Home</a>
                    <a href="pages/dashboard.php">Dashboard</a>
                    <?php if (isAdmin()): ?>
                        <a href="pages/admin.php">Admin</a>
                    <?php endif; ?>
                    <a href="pages/logout.php" class="logout-btn">Logout</a>
                <?php else: ?>
                    <a href="pages/login.php">Login</a>
                    <a href="pages/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container">