<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    $tasks = getTasks();
    
    // Add new task
    $newTask = [
        'text' => trim($_POST['task']),
        'completed' => false,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $tasks[] = $newTask;
    saveTasks($tasks);
}

header('Location: index.php');
exit;
?>