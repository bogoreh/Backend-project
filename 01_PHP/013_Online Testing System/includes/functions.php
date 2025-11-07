<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function redirect($page) {
    header("Location: $page");
    exit();
}

function calculateScore($user_answers, $test_id, $pdo) {
    $score = 0;
    $stmt = $pdo->prepare("SELECT id, correct_answer FROM questions WHERE test_id = ?");
    $stmt->execute([$test_id]);
    $questions = $stmt->fetchAll();
    
    foreach ($questions as $question) {
        if (isset($user_answers[$question['id']]) && $user_answers[$question['id']] === $question['correct_answer']) {
            $score++;
        }
    }
    
    return $score;
}
?>