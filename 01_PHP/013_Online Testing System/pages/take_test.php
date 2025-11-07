<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('../index.php');
}

$test_id = $_GET['test_id'] ?? null;

if (!$test_id) {
    redirect('dashboard.php');
}

// Get test details
$stmt = $pdo->prepare("SELECT * FROM tests WHERE id = ?");
$stmt->execute([$test_id]);
$test = $stmt->fetch();

if (!$test) {
    redirect('dashboard.php');
}

// Get questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE test_id = ?");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = calculateScore($_POST, $test_id, $pdo);
    $total_questions = count($questions);
    
    // Save result
    $stmt = $pdo->prepare("INSERT INTO test_results (user_id, test_id, score, total_questions) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $test_id, $score, $total_questions]);
    
    $_SESSION['test_result'] = [
        'score' => $score,
        'total' => $total_questions,
        'test_title' => $test['title']
    ];
    
    redirect('results.php');
}
?>

<?php include '../includes/header.php'; ?>

<div class="test-container">
    <div class="test-header">
        <h1><?php echo $test['title']; ?></h1>
        <p><?php echo $test['description']; ?></p>
        <p><strong>Time Limit:</strong> <?php echo $test['time_limit']; ?> minutes</p>
    </div>
    
    <form method="POST" action="" class="test-form">
        <?php foreach ($questions as $index => $question): ?>
            <div class="question-card">
                <h3>Question <?php echo $index + 1; ?></h3>
                <p class="question-text"><?php echo $question['question_text']; ?></p>
                
                <div class="options">
                    <label class="option">
                        <input type="radio" name="<?php echo $question['id']; ?>" value="a" required>
                        <?php echo $question['option_a']; ?>
                    </label>
                    <label class="option">
                        <input type="radio" name="<?php echo $question['id']; ?>" value="b">
                        <?php echo $question['option_b']; ?>
                    </label>
                    <label class="option">
                        <input type="radio" name="<?php echo $question['id']; ?>" value="c">
                        <?php echo $question['option_c']; ?>
                    </label>
                    <label class="option">
                        <input type="radio" name="<?php echo $question['id']; ?>" value="d">
                        <?php echo $question['option_d']; ?>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary btn-full">Submit Test</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>