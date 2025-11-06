<?php
require_once __DIR__ . '/../includes/AdManager.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Dispenser Demo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Ad Dispenser Demo</h1>
        </header>
        
        <main>
            <div class="content">
                <h2>Welcome to Our Website</h2>
                <p>This is a demo page showing how ads are displayed.</p>
                
                <div class="ad-container">
                    <h3>Sponsored Ad</h3>
                    <?php
                    $adManager = new AdManager();
                    echo $adManager->displayAd();
                    ?>
                </div>
                
                <div class="website-content">
                    <p>More website content here...</p>
                </div>
            </div>
        </main>
        
        <footer>
            <p>&copy; 2024 Ad Dispenser. All rights reserved.</p>
        </footer>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>