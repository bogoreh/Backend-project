<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$survey_id = $_GET['id'];
$survey = getSurvey($survey_id);
$questions = getQuestions($survey_id);

// Get response counts
$response_counts = [];
foreach ($questions as $question) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM responses WHERE question_id = ?");
    $stmt->execute([$question['id']]);
    $response_counts[$question['id']] = $stmt->fetchColumn();
}
?>

<?php include '../includes/header.php'; ?>

<div class="results-container">
    <div class="results-header">
        <h1>Survey Results: <?= htmlspecialchars($survey['title']) ?></h1>
        <p><?= htmlspecialchars($survey['description']) ?></p>
    </div>

    <?php foreach ($questions as $index => $question): ?>
        <div class="result-card">
            <div class="result-header">
                <h3>Q<?= $index + 1 ?>: <?= htmlspecialchars($question['question_text']) ?></h3>
                <span class="response-count">
                    <?= $response_counts[$question['id']] ?> responses
                </span>
            </div>

            <div class="result-content">
                <?php if ($question['question_type'] === 'text'): ?>
                    <?php
                    $stmt = $pdo->prepare("SELECT answer FROM responses WHERE question_id = ? AND answer != ''");
                    $stmt->execute([$question['id']]);
                    $text_answers = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    ?>
                    
                    <?php if (empty($text_answers)): ?>
                        <p class="no-responses">No text responses yet.</p>
                    <?php else: ?>
                        <div class="text-responses">
                            <?php foreach ($text_answers as $answer): ?>
                                <div class="text-response">
                                    "<?= htmlspecialchars($answer) ?>"
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <?php
                    $options = json_decode($question['options']);
                    $stmt = $pdo->prepare("SELECT answer FROM responses WHERE question_id = ?");
                    $stmt->execute([$question['id']]);
                    $all_answers = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    $answer_counts = [];
                    foreach ($options as $option) {
                        $answer_counts[$option] = 0;
                    }
                    
                    foreach ($all_answers as $answer_json) {
                        $answers = json_decode($answer_json, true);
                        if (is_array($answers)) {
                            foreach ($answers as $ans) {
                                if (isset($answer_counts[$ans])) {
                                    $answer_counts[$ans]++;
                                }
                            }
                        } else {
                            if (isset($answer_counts[$answer_json])) {
                                $answer_counts[$answer_json]++;
                            }
                        }
                    }
                    ?>
                    
                    <div class="chart-container">
                        <?php foreach ($answer_counts as $option => $count): ?>
                            <div class="chart-item">
                                <div class="chart-label"><?= htmlspecialchars($option) ?></div>
                                <div class="chart-bar">
                                    <div class="chart-fill" style="width: <?= $response_counts[$question['id']] > 0 ? ($count / $response_counts[$question['id']] * 100) : 0 ?>%">
                                        <span class="chart-percentage">
                                            <?= $response_counts[$question['id']] > 0 ? round(($count / $response_counts[$question['id']]) * 100) : 0 ?>%
                                        </span>
                                    </div>
                                </div>
                                <div class="chart-count">(<?= $count ?>)</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="results-actions">
        <a href="../index.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Surveys
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>