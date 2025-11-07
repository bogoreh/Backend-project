<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1><a href="index.php">Simple CMS</a></h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="admin/dashboard.php">Dashboard</a></li>
                        <li><a href="admin/login.php?logout=true">Logout</a></li>
                    <?php else: ?>
                        <li><a href="admin/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">