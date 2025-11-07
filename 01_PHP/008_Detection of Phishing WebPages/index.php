<?php
require_once 'includes/header.php';
require_once 'classes/PhishingDetector.php';

$result = null;
$url = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';
    $detector = new PhishingDetector();
    $result = $detector->analyzeUrl($url);
}
?>

<div class="container">
    <h1>Phishing Website Detector</h1>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="url">Enter URL to check:</label>
            <input type="text" id="url" name="url" 
                   value="<?php echo htmlspecialchars($url); ?>" 
                   placeholder="https://example.com" required>
        </div>
        <button type="submit">Check URL</button>
    </form>

    <?php if ($result): ?>
    <div class="result <?php echo $result['is_suspicious'] ? 'suspicious' : 'safe'; ?>">
        <h3>Analysis Result for: <?php echo htmlspecialchars($url); ?></h3>
        
        <div class="score">
            Risk Score: <?php echo $result['risk_score']; ?>%
        </div>
        
        <div class="details">
            <h4>Details:</h4>
            <ul>
                <?php foreach ($result['reasons'] as $reason): ?>
                    <li><?php echo htmlspecialchars($reason); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="verdict">
            <strong>Verdict:</strong> 
            <?php echo $result['is_suspicious'] ? '⚠️ SUSPICIOUS - Possible Phishing Site' : '✅ SAFE - Likely Legitimate'; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>