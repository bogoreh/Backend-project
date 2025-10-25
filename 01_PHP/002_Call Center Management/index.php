<?php
require_once 'config/database.php';

// Simple routing
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

switch($page) {
    case 'agents':
        require_once 'controllers/AgentController.php';
        $controller = new AgentController();
        
        switch($action) {
            case 'create':
                if($_POST) {
                    if($controller->create($_POST)) {
                        header("Location: index.php?page=agents");
                    }
                } else {
                    include 'views/agents/create.php';
                }
                break;
            case 'edit':
                // Implement edit functionality
                break;
            case 'delete':
                if(isset($_GET['id'])) {
                    $controller->delete($_GET['id']);
                    header("Location: index.php?page=agents");
                }
                break;
            default:
                $agents = $controller->index();
                include 'views/agents/index.php';
        }
        break;
        
    case 'calls':
        // Similar structure for calls
        break;
        
    case 'customers':
        // Similar structure for customers
        break;
        
    default:
        // Dashboard
        include 'views/dashboard.php';
}
?>