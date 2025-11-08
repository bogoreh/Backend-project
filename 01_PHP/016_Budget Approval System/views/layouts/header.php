<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Approval System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">💰 BudgetPro</a>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['username'])): ?>
                    <span class="nav-item nav-link">Welcome, <?php echo $_SESSION['username']; ?>!</span>
                    <a class="nav-item nav-link" href="dashboard.php">Dashboard</a>
                    <a class="nav-item nav-link" href="index.php">Budgets</a>
                    <a class="nav-item nav-link" href="?logout=true">Logout</a>
                <?php else: ?>
                    <a class="nav-item nav-link" href="login.php">Login</a>
                    <a class="nav-item nav-link" href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>