<?php
session_start();
require_once '../models/Database.php';
require_once '../models/Budget.php';
require_once '../models/User.php';

class DashboardController {
    private $db;
    private $budget;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->budget = new Budget($this->db);
        $this->user = new User($this->db);
    }

    public function getDashboardData($user_id, $role) {
        $data = [];
        
        // Get all budgets for stats
        $allBudgets = $this->budget->read();
        $userBudgets = $this->budget->readByUser($user_id);
        
        // Calculate statistics
        $totalBudgets = 0;
        $pendingBudgets = 0;
        $approvedBudgets = 0;
        $rejectedBudgets = 0;
        $totalAmount = 0;
        $userTotalAmount = 0;

        // Process all budgets
        while($budget = $allBudgets->fetch(PDO::FETCH_ASSOC)) {
            $totalBudgets++;
            $totalAmount += $budget['amount'];
            
            switch($budget['status']) {
                case 'pending':
                    $pendingBudgets++;
                    break;
                case 'approved':
                    $approvedBudgets++;
                    break;
                case 'rejected':
                    $rejectedBudgets++;
                    break;
            }
        }

        // Process user budgets for user-specific stats
        $userBudgetsForStats = $this->budget->readByUser($user_id);
        while($budget = $userBudgetsForStats->fetch(PDO::FETCH_ASSOC)) {
            $userTotalAmount += $budget['amount'];
        }

        // Get recent budgets
        $recentBudgets = $this->getRecentBudgets($user_id, $role);

        $data = [
            'total_budgets' => $totalBudgets,
            'pending_budgets' => $pendingBudgets,
            'approved_budgets' => $approvedBudgets,
            'rejected_budgets' => $rejectedBudgets,
            'total_amount' => $totalAmount,
            'user_total_amount' => $userTotalAmount,
            'recent_budgets' => $recentBudgets
        ];

        return $data;
    }

    private function getRecentBudgets($user_id, $role) {
        if($role === 'admin' || $role === 'manager') {
            $stmt = $this->budget->read();
        } else {
            $stmt = $this->budget->readByUser($user_id);
        }

        $recentBudgets = [];
        $count = 0;
        
        while($budget = $stmt->fetch(PDO::FETCH_ASSOC) && $count < 5) {
            $recentBudgets[] = $budget;
            $count++;
        }

        return $recentBudgets;
    }

    public function getUserStats($user_id) {
        $stmt = $this->budget->readByUser($user_id);
        
        $userStats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'total_amount' => 0
        ];

        while($budget = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userStats['total']++;
            $userStats['total_amount'] += $budget['amount'];
            
            switch($budget['status']) {
                case 'pending':
                    $userStats['pending']++;
                    break;
                case 'approved':
                    $userStats['approved']++;
                    break;
                case 'rejected':
                    $userStats['rejected']++;
                    break;
            }
        }

        return $userStats;
    }
}

// Handle AJAX requests for dashboard data
if(isset($_GET['action']) && $_GET['action'] === 'get_stats' && isset($_SESSION['user_id'])) {
    $dashboardController = new DashboardController();
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    
    $data = $dashboardController->getDashboardData($user_id, $role);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
?>