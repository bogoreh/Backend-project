<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../index.php');
}

if (!isset($_SESSION['test_result'])) {
    redirect('dashboard.php');
}

$result = $_SESSION['test_result'];
unset($_SESSION['test_result']);

$percentage = ($result['score'] / $result['total']) * 100;
?>

<?php include '../includes/header.php'; ?>

<div class="results-container">
    <div class="results-card">
        <div class="results-header">
            <h1>Test Results</h1>
            <p><?php echo $result['test_title']; ?></p>
        </div>
        
        <div class="score-display">
            <div class="score-circle">
                <span class="score-percentage"><?php echo round($percentage); ?>%</span>
            </div>
            <div class="score-details">
                <h3>Your Score: <?php echo $result['score']; ?>/<?php echo $result['total']; ?></h3>
                <p><?php echo $percentage >= 70 ? 'Congratulations! You passed!' : 'Keep practicing!'; ?></p>
            </div>
        </div>
        
        <div class="results-actions">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            <a href="../index.php" class="btn btn-secondary">Home</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>