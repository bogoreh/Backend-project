<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../index.php');
}

$admin_id = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_test'])) {
        // Create new test
        $title = $_POST['title'];
        $description = $_POST['description'];
        $time_limit = $_POST['time_limit'];
        
        $stmt = $pdo->prepare("INSERT INTO tests (title, description, time_limit, created_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $time_limit, $admin_id]);
        $test_id = $pdo->lastInsertId();
        
        $_SESSION['success'] = "Test created successfully!";
        redirect("admin.php?action=add_questions&test_id=$test_id");
    }
    
    if (isset($_POST['add_questions'])) {
        // Add questions to test
        $test_id = $_POST['test_id'];
        $questions = $_POST['questions'];
        
        foreach ($questions as $question) {
            if (!empty($question['text'])) {
                $stmt = $pdo->prepare("INSERT INTO questions (test_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $test_id,
                    $question['text'],
                    $question['option_a'],
                    $question['option_b'],
                    $question['option_c'],
                    $question['option_d'],
                    $question['correct_answer']
                ]);
            }
        }
        
        $_SESSION['success'] = "Questions added successfully!";
        redirect("admin.php");
    }
    
    if (isset($_POST['delete_test'])) {
        $test_id = $_POST['test_id'];
        $stmt = $pdo->prepare("DELETE FROM tests WHERE id = ?");
        $stmt->execute([$test_id]);
        $_SESSION['success'] = "Test deleted successfully!";
        redirect("admin.php");
    }
}

// Get statistics
$users_count = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'student'")->fetchColumn();
$tests_count = $pdo->query("SELECT COUNT(*) FROM tests")->fetchColumn();
$results_count = $pdo->query("SELECT COUNT(*) FROM test_results")->fetchColumn();

// Get all tests with question counts
$tests_stmt = $pdo->query("
    SELECT t.*, 
           COUNT(q.id) as question_count,
           COUNT(tr.id) as attempt_count,
           u.username as created_by
    FROM tests t 
    LEFT JOIN questions q ON t.id = q.test_id 
    LEFT JOIN test_results tr ON t.id = tr.test_id
    LEFT JOIN users u ON t.created_by = u.id
    GROUP BY t.id
    ORDER BY t.created_at DESC
");
$tests = $tests_stmt->fetchAll();

// Get recent results
$recent_results_stmt = $pdo->query("
    SELECT tr.*, u.username, t.title 
    FROM test_results tr 
    JOIN users u ON tr.user_id = u.id 
    JOIN tests t ON tr.test_id = t.id 
    ORDER BY tr.completed_at DESC 
    LIMIT 10
");
$recent_results = $recent_results_stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="admin-container">
    <!-- Admin Header -->
    <div class="admin-header">
        <h1><i class="fas fa-cog"></i> Admin Dashboard</h1>
        <p>Manage tests, view results, and monitor system activity</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $users_count; ?></h3>
                <p>Total Students</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $tests_count; ?></h3>
                <p>Total Tests</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $results_count; ?></h3>
                <p>Test Attempts</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo round($results_count / max($users_count, 1)); ?></h3>
                <p>Avg Tests per Student</p>
            </div>
        </div>
    </div>

    <!-- Main Admin Content -->
    <div class="admin-content">
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="showCreateTestForm()">
                    <i class="fas fa-plus"></i> Create New Test
                </button>
                <a href="#tests-section" class="btn btn-secondary">
                    <i class="fas fa-list"></i> View All Tests
                </a>
                <a href="#results-section" class="btn btn-secondary">
                    <i class="fas fa-chart-bar"></i> View Results
                </a>
            </div>
        </div>

        <!-- Create Test Form (Initially Hidden) -->
        <div id="create-test-form" class="create-test-form" style="display: none;">
            <div class="form-card">
                <h3><i class="fas fa-plus"></i> Create New Test</h3>
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Test Title:</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="time_limit">Time Limit (minutes):</label>
                            <input type="number" id="time_limit" name="time_limit" value="30" min="1" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Test Description:</label>
                        <textarea id="description" name="description" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="create_test" class="btn btn-primary">Create Test</button>
                        <button type="button" class="btn btn-secondary" onclick="hideCreateTestForm()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Questions Form -->
        <?php if (isset($_GET['action']) && $_GET['action'] === 'add_questions' && isset($_GET['test_id'])): 
            $test_id = $_GET['test_id'];
            $test_stmt = $pdo->prepare("SELECT * FROM tests WHERE id = ?");
            $test_stmt->execute([$test_id]);
            $current_test = $test_stmt->fetch();
        ?>
            <div class="add-questions-form">
                <div class="form-card">
                    <h3><i class="fas fa-question-circle"></i> Add Questions to: <?php echo $current_test['title']; ?></h3>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                        
                        <div id="questions-container">
                            <!-- Question template will be added here by JavaScript -->
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="addQuestion()">
                                <i class="fas fa-plus"></i> Add Another Question
                            </button>
                            <button type="submit" name="add_questions" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save All Questions
                            </button>
                            <a href="admin.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <script>
                // Add first question when page loads
                document.addEventListener('DOMContentLoaded', function() {
                    addQuestion();
                });
            </script>
        <?php endif; ?>

        <!-- Tests Management Section -->
        <div id="tests-section" class="management-section">
            <h2><i class="fas fa-file-alt"></i> Tests Management</h2>
            
            <?php if (empty($tests)): ?>
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <h3>No Tests Created Yet</h3>
                    <p>Create your first test to get started!</p>
                </div>
            <?php else: ?>
                <div class="tests-grid">
                    <?php foreach ($tests as $test): ?>
                        <div class="test-management-card">
                            <div class="test-header">
                                <h4><?php echo $test['title']; ?></h4>
                                <span class="test-meta">
                                    <?php echo $test['question_count']; ?> Questions | 
                                    <?php echo $test['attempt_count']; ?> Attempts
                                </span>
                            </div>
                            
                            <div class="test-description">
                                <p><?php echo $test['description']; ?></p>
                            </div>
                            
                            <div class="test-details">
                                <div class="test-detail">
                                    <strong>Time Limit:</strong> <?php echo $test['time_limit']; ?> minutes
                                </div>
                                <div class="test-detail">
                                    <strong>Created By:</strong> <?php echo $test['created_by']; ?>
                                </div>
                                <div class="test-detail">
                                    <strong>Created:</strong> <?php echo date('M j, Y', strtotime($test['created_at'])); ?>
                                </div>
                            </div>
                            
                            <div class="test-actions">
                                <?php if ($test['question_count'] == 0): ?>
                                    <a href="admin.php?action=add_questions&test_id=<?php echo $test['id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-question-circle"></i> Add Questions
                                    </a>
                                <?php else: ?>
                                    <span class="status-badge status-complete">Ready</span>
                                <?php endif; ?>
                                
                                <form method="POST" action="" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this test?');">
                                    <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
                                    <button type="submit" name="delete_test" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Results Section -->
        <div id="results-section" class="management-section">
            <h2><i class="fas fa-chart-bar"></i> Recent Test Results</h2>
            
            <?php if (empty($recent_results)): ?>
                <div class="empty-state">
                    <i class="fas fa-chart-bar"></i>
                    <h3>No Results Yet</h3>
                    <p>Test results will appear here once students start taking tests.</p>
                </div>
            <?php else: ?>
                <div class="results-table-container">
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Test</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_results as $result): 
                                $percentage = round(($result['score'] / $result['total_questions']) * 100);
                            ?>
                                <tr>
                                    <td><?php echo $result['username']; ?></td>
                                    <td><?php echo $result['title']; ?></td>
                                    <td><?php echo $result['score']; ?>/<?php echo $result['total_questions']; ?></td>
                                    <td>
                                        <span class="score-badge <?php echo $percentage >= 70 ? 'score-high' : ($percentage >= 50 ? 'score-medium' : 'score-low'); ?>">
                                            <?php echo $percentage; ?>%
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($result['completed_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Show/hide create test form
function showCreateTestForm() {
    document.getElementById('create-test-form').style.display = 'block';
    document.getElementById('create-test-form').scrollIntoView({ behavior: 'smooth' });
}

function hideCreateTestForm() {
    document.getElementById('create-test-form').style.display = 'none';
}

// Question management functions
let questionCount = 0;

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questions-container');
    
    const questionHTML = `
        <div class="question-form" id="question-${questionCount}">
            <div class="question-header">
                <h4>Question ${questionCount}</h4>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(${questionCount})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="form-group">
                <label>Question Text:</label>
                <textarea name="questions[${questionCount}][text]" rows="3" required></textarea>
            </div>
            
            <div class="options-grid">
                <div class="form-group">
                    <label>Option A:</label>
                    <input type="text" name="questions[${questionCount}][option_a]" required>
                </div>
                <div class="form-group">
                    <label>Option B:</label>
                    <input type="text" name="questions[${questionCount}][option_b]" required>
                </div>
                <div class="form-group">
                    <label>Option C:</label>
                    <input type="text" name="questions[${questionCount}][option_c]" required>
                </div>
                <div class="form-group">
                    <label>Option D:</label>
                    <input type="text" name="questions[${questionCount}][option_d]" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Correct Answer:</label>
                <select name="questions[${questionCount}][correct_answer]" required>
                    <option value="">Select correct answer</option>
                    <option value="a">Option A</option>
                    <option value="b">Option B</option>
                    <option value="c">Option C</option>
                    <option value="d">Option D</option>
                </select>
            </div>
            
            <hr>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHTML);
}

function removeQuestion(id) {
    const element = document.getElementById(`question-${id}`);
    if (element) {
        element.remove();
        // Renumber remaining questions
        questionCount = 0;
        const questions = document.querySelectorAll('.question-form');
        questions.forEach((question, index) => {
            questionCount++;
            question.querySelector('h4').textContent = `Question ${questionCount}`;
        });
    }
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>

<?php include '../includes/footer.php'; ?>