<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../index.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tests");
$tests = $stmt->fetchAll();

$results_stmt = $pdo->prepare("
    SELECT tr.*, t.title 
    FROM test_results tr 
    JOIN tests t ON tr.test_id = t.id 
    WHERE tr.user_id = ? 
    ORDER BY tr.completed_at DESC
");
$results_stmt->execute([$user_id]);
$results = $results_stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="dashboard-header">
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Your testing dashboard</p>
</div>

<div class="dashboard-grid">
    <div class="dashboard-card">
        <h3>Available Tests</h3>
        <div class="test-list">
            <?php foreach ($tests as $test): ?>
                <div class="test-item">
                    <h4><?php echo $test['title']; ?></h4>
                    <p><?php echo $test['description']; ?></p>
                    <p><strong>Time Limit:</strong> <?php echo $test['time_limit']; ?> minutes</p>
                    <a href="take_test.php?test_id=<?php echo $test['id']; ?>" class="btn btn-primary">Take Test</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="dashboard-card">
        <h3>Your Results</h3>
        <div class="results-list">
            <?php if (empty($results)): ?>
                <p>No test results yet.</p>
            <?php else: ?>
                <?php foreach ($results as $result): ?>
                    <div class="result-item">
                        <h4><?php echo $result['title']; ?></h4>
                        <p>Score: <?php echo $result['score']; ?>/<?php echo $result['total_questions']; ?></p>
                        <p>Completed: <?php echo date('M j, Y g:i A', strtotime($result['completed_at'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>