<?php
include 'config/database.php';

$stmt = $pdo->query("
    SELECT m.*, u.username 
    FROM messages m 
    JOIN users u ON m.user_id = u.id 
    ORDER BY m.created_at DESC 
    LIMIT 50
");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get online users
$stmt = $pdo->query("
    SELECT u.username 
    FROM online_users ou 
    JOIN users u ON ou.user_id = u.id 
    ORDER BY u.username
");
$onlineUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    'messages' => array_reverse($messages),
    'onlineUsers' => $onlineUsers
]);
?>