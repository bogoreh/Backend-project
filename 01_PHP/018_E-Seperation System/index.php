<?php
session_start();
require_once 'config/database.php';
require_once 'models/Separation.php';

$database = new Database();
$db = $database->getConnection();
$separation = new Separation($db);

$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
$message = '';
$message_type = '';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['employee_name'])) {
        // Create new separation
        $separation->employee_name = $_POST['employee_name'];
        $separation->employee_id = $_POST['employee_id'];
        $separation->department = $_POST['department'];
        $separation->separation_date = $_POST['separation_date'];
        $separation->reason = $_POST['reason'];
        $separation->status = $_POST['status'];

        if ($separation->create()) {
            $message = "Separation request submitted successfully!";
            $message_type = "success";
        } else {
            $message = "Unable to submit separation request.";
            $message_type = "danger";
        }
    } elseif (isset($_POST['status'])) {
        // Update status
        $separation->id = $_POST['id'];
        $separation->status = $_POST['status'];

        if ($separation->updateStatus()) {
            $message = "Status updated successfully!";
            $message_type = "success";
        } else {
            $message = "Unable to update status.";
            $message_type = "danger";
        }
    }
}

// Include header
include 'views/header.php';

// Display appropriate view based on action
switch ($action) {
    case 'add':
        include 'views/add_separation.php';
        break;
    case 'view':
        $separations = $separation->read();
        include 'views/view_separations.php';
        break;
    default:
        include 'views/dashboard.php';
        break;
}

// Include footer
include 'views/footer.php';
?>