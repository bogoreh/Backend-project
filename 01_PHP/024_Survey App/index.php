<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$surveys = getSurveys();
?>

<?php include 'includes/header.php'; ?>

<div class="hero">
    <div class="hero-content">
        <h1>Welcome to SurveyApp</h1>
        <p>Collect valuable feedback from your users with our easy-to-use survey platform</p>
        <a href="surveys/create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Survey
        </a>
    </div>
</div>

<div class="surveys-grid">
    <h2>Available Surveys</h2>
    
    <?php if (empty($surveys)): ?>
        <div class="no-surveys">
            <i class="fas fa-clipboard-list"></i>
            <h3>No surveys available</h3>
            <p>Create your first survey to get started!</p>
        </div>
    <?php else: ?>
        <div class="survey-cards">
            <?php foreach ($surveys as $survey): ?>
                <div class="survey-card">
                    <div class="survey-header">
                        <h3><?= htmlspecialchars($survey['title']) ?></h3>
                        <span class="survey-date">
                            <?= date('M j, Y', strtotime($survey['created_at'])) ?>
                        </span>
                    </div>
                    <p class="survey-description">
                        <?= htmlspecialchars($survey['description']) ?>
                    </p>
                    <div class="survey-actions">
                        <a href="surveys/view.php?id=<?= $survey['id'] ?>" class="btn btn-outline">
                            <i class="fas fa-eye"></i> View Survey
                        </a>
                        <a href="results/view.php?id=<?= $survey['id'] ?>" class="btn btn-secondary">
                            <i class="fas fa-chart-bar"></i> Results
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>