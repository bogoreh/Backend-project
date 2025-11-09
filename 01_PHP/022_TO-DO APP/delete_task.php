<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $tasks = getTasks();
    $taskId = (int)$_POST['task_id'];
    
    if (isset($tasks[$taskId])) {
        // Remove the task
        array_splice($tasks, $taskId, 1);
        saveTasks($tasks);
    }
}

header('Location: index.php');
exit;
?>