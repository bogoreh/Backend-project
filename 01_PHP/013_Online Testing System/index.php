<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
?>

<?php include 'includes/header.php'; ?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Welcome to Online Testing System</h1>
        <p>Test your knowledge with our comprehensive online assessment platform</p>
        <?php if (!isLoggedIn()): ?>
            <div class="hero-buttons">
                <a href="pages/register.php" class="btn btn-primary">Get Started</a>
                <a href="pages/login.php" class="btn btn-secondary">Login</a>
            </div>
        <?php else: ?>
            <div class="hero-buttons">
                <a href="pages/dashboard.php" class="btn btn-primary">Go to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="features-section">
    <div class="features-grid">
        <div class="feature-card">
            <i class="fas fa-clock"></i>
            <h3>Timed Tests</h3>
            <p>Complete tests within specified time limits</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-chart-bar"></i>
            <h3>Instant Results</h3>
            <p>Get immediate feedback on your performance</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-trophy"></i>
            <h3>Track Progress</h3>
            <p>Monitor your improvement over time</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>