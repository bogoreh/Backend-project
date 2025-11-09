<?php
// Configuration file
define('DATA_FILE', 'data/tasks.json');

// Initialize tasks file if it doesn't exist
if (!file_exists(DATA_FILE)) {
    file_put_contents(DATA_FILE, json_encode([]));
}

// Function to read tasks from JSON file
function getTasks() {
    $tasksJson = file_get_contents(DATA_FILE);
    return json_decode($tasksJson, true) ?: [];
}

// Function to save tasks to JSON file
function saveTasks($tasks) {
    file_put_contents(DATA_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}
?>