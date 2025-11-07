<?php
session_start();

// Include configuration and classes
include 'config/database.php';
include 'models/Resource.php';
include 'controllers/ResourceController.php';

// Initialize database connection and controller
$database = new Database();
$db = $database->getConnection();
$resourceController = new ResourceController($db);

// Get action from URL
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Route requests
switch($action) {
    case 'index':
        $resources = $resourceController->index();
        include 'views/resources/index.php';
        break;
        
    case 'create':
        include 'views/resources/create.php';
        break;
        
    case 'store':
        if($_POST) {
            if($resourceController->create($_POST)) {
                $_SESSION['message'] = "Resource created successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Unable to create resource.";
                $_SESSION['message_type'] = "danger";
            }
        }
        header("Location: index.php");
        break;
        
    case 'edit':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $resource = $resourceController->getResource($id);
        if($resource) {
            include 'views/resources/edit.php';
        } else {
            $_SESSION['message'] = "Resource not found.";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php");
        }
        break;
        
    case 'update':
        if($_POST) {
            $id = $_POST['id'];
            if($resourceController->update($id, $_POST)) {
                $_SESSION['message'] = "Resource updated successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Unable to update resource.";
                $_SESSION['message_type'] = "danger";
            }
        }
        header("Location: index.php");
        break;
        
    case 'delete':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        if($resourceController->delete($id)) {
            $_SESSION['message'] = "Resource deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Unable to delete resource.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: index.php");
        break;
        
    default:
        $resources = $resourceController->index();
        include 'views/resources/index.php';
        break;
}
?>