<?php include '../layouts/header.php'; ?>

<div class="login-container">
    <div class="card login-card">
        <div class="card-header">
            <h2>Login to BudgetPro</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="../../controllers/AuthController.php">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="auth-links">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>