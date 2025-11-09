<?php
function getSurveys() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM surveys ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSurvey($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getQuestions($survey_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE survey_id = ?");
    $stmt->execute([$survey_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveResponse($survey_id, $question_id, $answer) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO responses (survey_id, question_id, answer) VALUES (?, ?, ?)");
    return $stmt->execute([$survey_id, $question_id, $answer]);
}
?>