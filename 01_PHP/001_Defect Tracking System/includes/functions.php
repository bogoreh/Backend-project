<?php
require_once '../config/database.php';

function getAllDefects() {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM defects ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return $stmt;
}

function getDefectById($id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM defects WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addDefect($title, $description, $priority, $assigned_to, $created_by) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO defects SET title=:title, description=:description, priority=:priority, assigned_to=:assigned_to, created_by=:created_by";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":priority", $priority);
    $stmt->bindParam(":assigned_to", $assigned_to);
    $stmt->bindParam(":created_by", $created_by);
    
    return $stmt->execute();
}

function updateDefect($id, $title, $description, $status, $priority, $assigned_to) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "UPDATE defects SET title=:title, description=:description, status=:status, priority=:priority, assigned_to=:assigned_to WHERE id=:id";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":priority", $priority);
    $stmt->bindParam(":assigned_to", $assigned_to);
    
    return $stmt->execute();
}

function deleteDefect($id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "DELETE FROM defects WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    
    return $stmt->execute();
}

function getStatusBadge($status) {
    $badges = [
        'Open' => 'bg-primary',
        'In Progress' => 'bg-warning',
        'Resolved' => 'bg-success',
        'Closed' => 'bg-secondary'
    ];
    
    return '<span class="badge ' . $badges[$status] . '">' . $status . '</span>';
}

function getPriorityBadge($priority) {
    $badges = [
        'Low' => 'bg-info',
        'Medium' => 'bg-primary',
        'High' => 'bg-warning',
        'Critical' => 'bg-danger'
    ];
    
    return '<span class="badge ' . $badges[$priority] . '">' . $priority . '</span>';
}
?>