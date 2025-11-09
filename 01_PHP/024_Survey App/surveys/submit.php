<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_POST) {
    try {
        $survey_id = $_POST['survey_id'];
        
        foreach ($_POST['answers'] as $question_id => $answer) {
            if (is_array($answer)) {
                // For checkbox questions, store as JSON
                $answer = json_encode($answer);
            }
            saveResponse($survey_id, $question_id, $answer);
        }
        
        $_SESSION['message'] = "Thank you for completing the survey!";
        header("Location: ../index.php");
        exit;
    } catch (Exception $e) {
        die("Error submitting survey: " . $e->getMessage());
    }
}

header("Location: ../index.php");
exit;
?>