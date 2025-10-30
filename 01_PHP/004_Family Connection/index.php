<?php
session_start();

// Include configuration and models
include_once 'config/database.php';
include_once 'models/Database.php';
include_once 'models/Family.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create default family if none exists
$family = new Family($db);
$families = $family->read();
if($families->rowCount() == 0) {
    $family->family_name = "Default Family";
    $family->create();
}

// Determine which view to show
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Include header
include 'views/header.php';

// Include the appropriate view
switch($action) {
    case 'add_member':
        include 'views/add_member.php';
        break;
    case 'view_family':
        include 'views/view_family.php';
        break;
    case 'home':
    default:
        include 'views/index.php';
        break;
}

// Include footer
include 'views/footer.php';
?>