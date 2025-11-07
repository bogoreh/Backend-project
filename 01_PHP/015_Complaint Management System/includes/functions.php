<?php
require_once 'config/database.php';

function addComplaint($name, $email, $subject, $complaint) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO complaints (name, email, subject, complaint) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    return $stmt->execute([$name, $email, $subject, $complaint]);
}

function getAllComplaints() {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM complaints ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateComplaintStatus($id, $status) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "UPDATE complaints SET status = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    
    return $stmt->execute([$status, $id]);
}

function getComplaintById($id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM complaints WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>