<?php
session_start();
require_once '../models/Database.php';
require_once '../models/Budget.php';

class BudgetController {
    private $db;
    private $budget;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->budget = new Budget($this->db);
    }

    public function create($data) {
        $this->budget->user_id = $_SESSION['user_id'];
        $this->budget->title = $data['title'];
        $this->budget->description = $data['description'];
        $this->budget->amount = $data['amount'];
        $this->budget->department = $data['department'];

        if($this->budget->create()) {
            $_SESSION['success'] = "Budget request submitted successfully!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to submit budget request.";
        }
    }

    public function updateStatus($id, $status) {
        if($this->budget->updateStatus($id, $status)) {
            $_SESSION['success'] = "Budget status updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update budget status.";
        }
        header("Location: index.php");
        exit();
    }

    public function getAllBudgets() {
        return $this->budget->read();
    }

    public function getUserBudgets($user_id) {
        return $this->budget->readByUser($user_id);
    }

    public function getBudget($id) {
        return $this->budget->getById($id);
    }
}

// Handle form submissions
if($_POST) {
    $budgetController = new BudgetController();
    
    if(isset($_POST['create_budget'])) {
        $budgetController->create($_POST);
    }
}

if(isset($_GET['action']) && isset($_GET['id'])) {
    $budgetController = new BudgetController();
    $budgetController->updateStatus($_GET['id'], $_GET['action']);
}
?>