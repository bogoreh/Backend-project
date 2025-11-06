<?php
require_once __DIR__ . '/../includes/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Create ads table
    $sql = "CREATE TABLE IF NOT EXISTS ads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT,
        type ENUM('banner', 'text', 'video') DEFAULT 'banner',
        image_url VARCHAR(500),
        target_url VARCHAR(500) NOT NULL,
        start_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        end_date DATETIME NULL,
        max_impressions INT DEFAULT 0,
        impressions INT DEFAULT 0,
        clicks INT DEFAULT 0,
        priority INT DEFAULT 0,
        status ENUM('active', 'inactive', 'expired') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    // Insert sample ads
    $sampleAds = [
        [
            'title' => 'Sample Banner Ad',
            'content' => 'This is a sample banner advertisement',
            'type' => 'banner',
            'image_url' => 'https://via.placeholder.com/728x90.png?text=Sample+Banner+Ad',
            'target_url' => 'https://example.com',
            'max_impressions' => 1000,
            'status' => 'active'
        ],
        [
            'title' => 'Sample Text Ad',
            'content' => 'This is a sample text advertisement with compelling content',
            'type' => 'text',
            'image_url' => '',
            'target_url' => 'https://example.com',
            'max_impressions' => 500,
            'status' => 'active'
        ]
    ];
    
    foreach ($sampleAds as $ad) {
        $stmt = $pdo->prepare("INSERT INTO ads (title, content, type, image_url, target_url, max_impressions, status) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $ad['title'],
            $ad['content'],
            $ad['type'],
            $ad['image_url'],
            $ad['target_url'],
            $ad['max_impressions'],
            $ad['status']
        ]);
    }
    
    echo "Database initialized successfully!\n";
    echo "Sample ads have been created.\n";
    
} catch (PDOException $e) {
    die("Database initialization failed: " . $e->getMessage());
}
?>