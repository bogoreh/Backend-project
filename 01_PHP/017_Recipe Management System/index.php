<?php
// Include configuration and classes
include 'config/database.php';
include 'models/Recipe.php';
include 'controllers/RecipeController.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize controller
$controller = new RecipeController($db);

// Determine action
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Route requests
switch($action) {
    case 'add':
        $controller->add();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}
?>