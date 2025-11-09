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

if (!$survey) {
    header("Location: ../index.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="survey-container">
    <div class="survey-header">
        <h1><?= htmlspecialchars($survey['title']) ?></h1>
        <p class="survey-description"><?= htmlspecialchars($survey['description']) ?></p>
    </div>

    <form method="POST" action="submit.php">
        <input type="hidden" name="survey_id" value="<?= $survey_id ?>">
        
        <?php foreach ($questions as $index => $question): ?>
            <div class="question-card">
                <div class="question-header">
                    <h3>Question <?= $index + 1 ?></h3>
                </div>
                <p class="question-text"><?= htmlspecialchars($question['question_text']) ?></p>
                
                <div class="answer-section">
                    <?php if ($question['question_type'] === 'text'): ?>
                        <textarea name="answers[<?= $question['id'] ?>]" rows="3" placeholder="Enter your answer..."></textarea>
                    
                    <?php elseif ($question['question_type'] === 'radio'): ?>
                        <?php $options = json_decode($question['options']); ?>
                        <?php foreach ($options as $option): ?>
                            <label class="radio-option">
                                <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= htmlspecialchars($option) ?>">
                                <?= htmlspecialchars($option) ?>
                            </label>
                        <?php endforeach; ?>
                    
                    <?php elseif ($question['question_type'] === 'checkbox'): ?>
                        <?php $options = json_decode($question['options']); ?>
                        <?php foreach ($options as $option): ?>
                            <label class="checkbox-option">
                                <input type="checkbox" name="answers[<?= $question['id'] ?>][]" value="<?= htmlspecialchars($option) ?>">
                                <?= htmlspecialchars($option) ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Submit Survey
            </button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>