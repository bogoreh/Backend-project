<?php
session_start();
require_once '../config/database.php';

if ($_POST) {
    try {
        // Save survey
        $stmt = $pdo->prepare("INSERT INTO surveys (title, description) VALUES (?, ?)");
        $stmt->execute([$_POST['title'], $_POST['description']]);
        $survey_id = $pdo->lastInsertId();

        // Save questions
        foreach ($_POST['questions'] as $question) {
            if (!empty($question['text'])) {
                $options = isset($question['options']) ? json_encode($question['options']) : null;
                $stmt = $pdo->prepare("INSERT INTO questions (survey_id, question_text, question_type, options) VALUES (?, ?, ?, ?)");
                $stmt->execute([$survey_id, $question['text'], $question['type'], $options]);
            }
        }

        $_SESSION['message'] = "Survey created successfully!";
        header("Location: ../index.php");
        exit;
    } catch (Exception $e) {
        $error = "Error creating survey: " . $e->getMessage();
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="form-container">
    <h1>Create New Survey</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" id="surveyForm">
        <div class="form-group">
            <label for="title">Survey Title</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"></textarea>
        </div>

        <div id="questions-container">
            <div class="question-item" data-index="0">
                <div class="question-header">
                    <h3>Question 1</h3>
                    <button type="button" class="btn btn-danger remove-question" onclick="removeQuestion(0)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="form-group">
                    <label>Question Text</label>
                    <input type="text" name="questions[0][text]" required>
                </div>
                <div class="form-group">
                    <label>Question Type</label>
                    <select name="questions[0][type]" onchange="toggleOptions(0, this.value)">
                        <option value="text">Text Answer</option>
                        <option value="radio">Multiple Choice</option>
                        <option value="checkbox">Checkboxes</option>
                    </select>
                </div>
                <div class="options-container" id="options-0" style="display: none;">
                    <label>Options (one per line)</label>
                    <textarea name="questions[0][options]" rows="4" placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="addQuestion()">
                <i class="fas fa-plus"></i> Add Question
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Survey
            </button>
        </div>
    </form>
</div>

<script>
let questionCount = 1;

function addQuestion() {
    const container = document.getElementById('questions-container');
    const newQuestion = document.querySelector('.question-item').cloneNode(true);
    
    newQuestion.setAttribute('data-index', questionCount);
    newQuestion.querySelector('h3').textContent = `Question ${questionCount + 1}`;
    newQuestion.querySelector('input[name="questions[0][text]"]').name = `questions[${questionCount}][text]`;
    newQuestion.querySelector('select[name="questions[0][type]"]').name = `questions[${questionCount}][type]`;
    newQuestion.querySelector('textarea[name="questions[0][options]"]').name = `questions[${questionCount}][options]`;
    newQuestion.querySelector('.remove-question').setAttribute('onclick', `removeQuestion(${questionCount})`);
    newQuestion.querySelector('.options-container').id = `options-${questionCount}`;
    
    container.appendChild(newQuestion);
    questionCount++;
}

function removeQuestion(index) {
    if (document.querySelectorAll('.question-item').length > 1) {
        document.querySelector(`[data-index="${index}"]`).remove();
        renumberQuestions();
    }
}

function renumberQuestions() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((question, index) => {
        question.setAttribute('data-index', index);
        question.querySelector('h3').textContent = `Question ${index + 1}`;
    });
    questionCount = questions.length;
}

function toggleOptions(index, type) {
    const optionsContainer = document.getElementById(`options-${index}`);
    optionsContainer.style.display = (type === 'radio' || type === 'checkbox') ? 'block' : 'none';
}
</script>

<?php include '../includes/footer.php'; ?>