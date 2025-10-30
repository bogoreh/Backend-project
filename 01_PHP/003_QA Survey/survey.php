<?php
include 'config/database.php';

$questions = [
    'Q1' => 'How often do you write unit tests for your code?',
    'Q2' => 'Do you perform code reviews regularly?',
    'Q3' => 'How would you rate your code documentation practices?',
    'Q4' => 'What testing frameworks do you use?',
    'Q5' => 'How important is code quality in your projects?'
];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($name) || empty($email)) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Insert survey
            $stmt = $pdo->prepare("INSERT INTO surveys (participant_name, email) VALUES (?, ?)");
            $stmt->execute([$name, $email]);
            $surveyId = $pdo->lastInsertId();
            
            // Insert responses
            $stmt = $pdo->prepare("INSERT INTO survey_responses (survey_id, question_code, answer) VALUES (?, ?, ?)");
            
            foreach ($questions as $code => $question) {
                if (isset($_POST[$code])) {
                    $answer = is_array($_POST[$code]) ? implode(', ', $_POST[$code]) : $_POST[$code];
                    $stmt->execute([$surveyId, $code, $answer]);
                }
            }
            
            $success = 'Thank you for completing the survey!';
            $_POST = []; // Clear form
        } catch(PDOException $e) {
            $error = 'Error submitting survey: ' . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="survey-container">
    <h2>Code QA Survey</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="survey.php">
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" value="<?php echo $_POST['name'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
        </div>
        
        <?php foreach ($questions as $code => $question): ?>
            <div class="question-group">
                <label class="question-label"><?php echo $question; ?></label>
                
                <?php if ($code === 'Q1'): ?>
                    <div class="options">
                        <label><input type="radio" name="Q1" value="Always" <?php echo ($_POST['Q1'] ?? '') === 'Always' ? 'checked' : ''; ?>> Always</label>
                        <label><input type="radio" name="Q1" value="Often" <?php echo ($_POST['Q1'] ?? '') === 'Often' ? 'checked' : ''; ?>> Often</label>
                        <label><input type="radio" name="Q1" value="Sometimes" <?php echo ($_POST['Q1'] ?? '') === 'Sometimes' ? 'checked' : ''; ?>> Sometimes</label>
                        <label><input type="radio" name="Q1" value="Rarely" <?php echo ($_POST['Q1'] ?? '') === 'Rarely' ? 'checked' : ''; ?>> Rarely</label>
                        <label><input type="radio" name="Q1" value="Never" <?php echo ($_POST['Q1'] ?? '') === 'Never' ? 'checked' : ''; ?>> Never</label>
                    </div>
                    
                <?php elseif ($code === 'Q2'): ?>
                    <div class="options">
                        <label><input type="radio" name="Q2" value="Yes, always" <?php echo ($_POST['Q2'] ?? '') === 'Yes, always' ? 'checked' : ''; ?>> Yes, always</label>
                        <label><input type="radio" name="Q2" value="Yes, sometimes" <?php echo ($_POST['Q2'] ?? '') === 'Yes, sometimes' ? 'checked' : ''; ?>> Yes, sometimes</label>
                        <label><input type="radio" name="Q2" value="No" <?php echo ($_POST['Q2'] ?? '') === 'No' ? 'checked' : ''; ?>> No</label>
                    </div>
                    
                <?php elseif ($code === 'Q3'): ?>
                    <div class="options">
                        <label><input type="radio" name="Q3" value="Excellent" <?php echo ($_POST['Q3'] ?? '') === 'Excellent' ? 'checked' : ''; ?>> Excellent</label>
                        <label><input type="radio" name="Q3" value="Good" <?php echo ($_POST['Q3'] ?? '') === 'Good' ? 'checked' : ''; ?>> Good</label>
                        <label><input type="radio" name="Q3" value="Average" <?php echo ($_POST['Q3'] ?? '') === 'Average' ? 'checked' : ''; ?>> Average</label>
                        <label><input type="radio" name="Q3" value="Poor" <?php echo ($_POST['Q3'] ?? '') === 'Poor' ? 'checked' : ''; ?>> Poor</label>
                    </div>
                    
                <?php elseif ($code === 'Q4'): ?>
                    <div class="options">
                        <label><input type="checkbox" name="Q4[]" value="PHPUnit" <?php echo in_array('PHPUnit', $_POST['Q4'] ?? []) ? 'checked' : ''; ?>> PHPUnit</label>
                        <label><input type="checkbox" name="Q4[]" value="Jest" <?php echo in_array('Jest', $_POST['Q4'] ?? []) ? 'checked' : ''; ?>> Jest</label>
                        <label><input type="checkbox" name="Q4[]" value="JUnit" <?php echo in_array('JUnit', $_POST['Q4'] ?? []) ? 'checked' : ''; ?>> JUnit</label>
                        <label><input type="checkbox" name="Q4[]" value="pytest" <?php echo in_array('pytest', $_POST['Q4'] ?? []) ? 'checked' : ''; ?>> pytest</label>
                        <label><input type="checkbox" name="Q4[]" value="Other" <?php echo in_array('Other', $_POST['Q4'] ?? []) ? 'checked' : ''; ?>> Other</label>
                    </div>
                    
                <?php elseif ($code === 'Q5'): ?>
                    <div class="options">
                        <select name="Q5">
                            <option value="">Select importance</option>
                            <option value="Very Important" <?php echo ($_POST['Q5'] ?? '') === 'Very Important' ? 'selected' : ''; ?>>Very Important</option>
                            <option value="Important" <?php echo ($_POST['Q5'] ?? '') === 'Important' ? 'selected' : ''; ?>>Important</option>
                            <option value="Somewhat Important" <?php echo ($_POST['Q5'] ?? '') === 'Somewhat Important' ? 'selected' : ''; ?>>Somewhat Important</option>
                            <option value="Not Important" <?php echo ($_POST['Q5'] ?? '') === 'Not Important' ? 'selected' : ''; ?>>Not Important</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary">Submit Survey</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>