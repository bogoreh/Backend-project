<?php
function getPosts($limit = null, $status = 'published') {
    global $pdo;
    
    $sql = "SELECT p.*, u.username 
            FROM posts p 
            LEFT JOIN users u ON p.author_id = u.id 
            WHERE p.status = ? 
            ORDER BY p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPost($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT p.*, u.username 
                          FROM posts p 
                          LEFT JOIN users u ON p.author_id = u.id 
                          WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createPost($title, $content, $excerpt, $author_id, $status = 'draft') {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, excerpt, author_id, status) 
                          VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$title, $content, $excerpt, $author_id, $status]);
}

function updatePost($id, $title, $content, $excerpt, $status) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, excerpt = ?, status = ? 
                          WHERE id = ?");
    return $stmt->execute([$title, $content, $excerpt, $status, $id]);
}

function deletePost($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    return $stmt->execute([$id]);
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>