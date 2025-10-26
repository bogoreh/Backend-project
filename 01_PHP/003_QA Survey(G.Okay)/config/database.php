<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'code_qa_survey');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Create tables if they don't exist
function initializeDatabase() {
    $pdo = getDBConnection();
    
    $sql = "CREATE TABLE IF NOT EXISTS surveys (
        id INT AUTO_INCREMENT PRIMARY KEY,
        participant_name VARCHAR(100),
        email VARCHAR(100),
        submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS survey_responses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        survey_id INT,
        question_code VARCHAR(50),
        answer TEXT,
        FOREIGN KEY (survey_id) REFERENCES surveys(id)
    )";
    $pdo->exec($sql);
}

initializeDatabase();
?>