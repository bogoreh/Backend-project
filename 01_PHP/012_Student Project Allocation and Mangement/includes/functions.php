<?php
function getAllStudents($pdo) {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProjects($pdo) {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAllocations($pdo) {
    $stmt = $pdo->query("
        SELECT a.*, s.name as student_name, s.student_id, p.title as project_title 
        FROM allocations a 
        JOIN students s ON a.student_id = s.id 
        JOIN projects p ON a.project_id = p.id 
        ORDER BY a.allocated_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addStudent($pdo, $student_id, $name, $email, $department) {
    $stmt = $pdo->prepare("INSERT INTO students (student_id, name, email, department) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$student_id, $name, $email, $department]);
}

function addProject($pdo, $title, $description, $supervisor, $max_students) {
    $stmt = $pdo->prepare("INSERT INTO projects (title, description, supervisor, max_students) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$title, $description, $supervisor, $max_students]);
}

function allocateProject($pdo, $student_id, $project_id) {
    // Check if student already has an allocation
    $stmt = $pdo->prepare("SELECT * FROM allocations WHERE student_id = ?");
    $stmt->execute([$student_id]);
    if ($stmt->rowCount() > 0) {
        return false; // Student already allocated
    }
    
    $stmt = $pdo->prepare("INSERT INTO allocations (student_id, project_id) VALUES (?, ?)");
    return $stmt->execute([$student_id, $project_id]);
}
?>