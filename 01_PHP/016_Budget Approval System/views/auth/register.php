<?php include '../layouts/header.php'; ?>

<div class="login-container">
    <div class="card login-card">
        <div class="card-header">
            <h2>Join BudgetPro</h2>
            <p class="mb-0">Create your account to start managing budgets</p>
        </div>
        <div class="card-body">
            <form method="POST" action="../../controllers/AuthController.php">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required 
                           placeholder="Enter your username">
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required 
                           placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required 
                           placeholder="Create a strong password">
                    <small class="text-muted">Must be at least 6 characters long</small>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required 
                           placeholder="Confirm your password">
                </div>

                <button type="submit" name="register" class="btn btn-primary w-100 mb-3">
                    Create Account
                </button>
                
                <div class="auth-links">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Client-side validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.querySelector('input[name="password"]');
    const confirmPassword = document.querySelector('input[name="confirm_password"]');
    
    if (password.value.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long');
        password.focus();
        return;
    }
    
    if (password.value !== confirmPassword.value) {
        e.preventDefault();
        alert('Passwords do not match');
        confirmPassword.focus();
        return;
    }
});
</script>

<?php include '../layouts/footer.php'; ?>