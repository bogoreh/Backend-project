<?php
include 'config/database.php';

try {
    $pdo = getDBConnection();
    
    // Get total surveys
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM surveys");
    $totalSurveys = $stmt->fetch()['total'];
    
    // Get responses for Q1
    $stmt = $pdo->query("SELECT answer, COUNT(*) as count FROM survey_responses WHERE question_code = 'Q1' GROUP BY answer");
    $q1Results = $stmt->fetchAll();
    
    // Get responses for Q2
    $stmt = $pdo->query("SELECT answer, COUNT(*) as count FROM survey_responses WHERE question_code = 'Q2' GROUP BY answer");
    $q2Results = $stmt->fetchAll();
    
    // Get responses for Q3
    $stmt = $pdo->query("SELECT answer, COUNT(*) as count FROM survey_responses WHERE question_code = 'Q3' GROUP BY answer");
    $q3Results = $stmt->fetchAll();
    
    // Get responses for Q5
    $stmt = $pdo->query("SELECT answer, COUNT(*) as count FROM survey_responses WHERE question_code = 'Q5' GROUP BY answer");
    $q5Results = $stmt->fetchAll();
    
} catch(PDOException $e) {
    die("Error fetching results: " . $e->getMessage());
}

include 'includes/header.php';
?>

<div class="results-container">
    <h2>Survey Results</h2>
    
    <div class="stats-overview">
        <div class="stat-card">
            <h3>Total Surveys</h3>
            <p class="stat-number"><?php echo $totalSurveys; ?></p>
        </div>
    </div>
    
    <div class="results-grid">
        <div class="result-chart">
            <h3>Unit Test Frequency (Q1)</h3>
            <?php foreach ($q1Results as $result): ?>
                <div class="chart-bar">
                    <span class="label"><?php echo $result['answer']; ?></span>
                    <div class="bar-container">
                        <div class="bar" style="width: <?php echo ($result['count'] / $totalSurveys) * 100; ?>%"></div>
                    </div>
                    <span class="count"><?php echo $result['count']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="result-chart">
            <h3>Code Review Practices (Q2)</h3>
            <?php foreach ($q2Results as $result): ?>
                <div class="chart-bar">
                    <span class="label"><?php echo $result['answer']; ?></span>
                    <div class="bar-container">
                        <div class="bar" style="width: <?php echo ($result['count'] / $totalSurveys) * 100; ?>%"></div>
                    </div>
                    <span class="count"><?php echo $result['count']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="result-chart">
            <h3>Documentation Quality (Q3)</h3>
            <?php foreach ($q3Results as $result): ?>
                <div class="chart-bar">
                    <span class="label"><?php echo $result['answer']; ?></span>
                    <div class="bar-container">
                        <div class="bar" style="width: <?php echo ($result['count'] / $totalSurveys) * 100; ?>%"></div>
                    </div>
                    <span class="count"><?php echo $result['count']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="result-chart">
            <h3>Code Quality Importance (Q5)</h3>
            <?php foreach ($q5Results as $result): ?>
                <div class="chart-bar">
                    <span class="label"><?php echo $result['answer']; ?></span>
                    <div class="bar-container">
                        <div class="bar" style="width: <?php echo ($result['count'] / $totalSurveys) * 100; ?>%"></div>
                    </div>
                    <span class="count"><?php echo $result['count']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>