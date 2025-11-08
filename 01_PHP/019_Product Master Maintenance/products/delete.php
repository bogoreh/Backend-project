<?php
include '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Product ID not found.');

try {
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    
    if($stmt->execute()){
        header("Location: index.php?message=Product deleted successfully");
    } else {
        header("Location: index.php?message=Unable to delete product");
    }
} catch(PDOException $exception){
    header("Location: index.php?message=Error: " . $exception->getMessage());
}
?>