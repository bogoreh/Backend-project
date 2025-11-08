<?php
include 'layouts/header.php';
require_once '../controllers/BudgetController.php';

$budgetController = new BudgetController();

// Get statistics
$allBudgets = $budgetController->getAllBudgets();
$userBudgets = $budgetController->getUserBudgets($_SESSION['user_id']);

$totalBudgets = 0;
$pendingBudgets = 0;
$approvedBudgets = 0;
$totalAmount = 0;

while($budget = $allBudgets->fetch(PDO::FETCH_ASSOC)) {
    $totalBudgets++;
    $totalAmount += $budget['amount'];
    if($budget['status'] === 'pending') $pendingBudgets++;
    if($budget['status'] === 'approved') $approvedBudgets++;
}
?>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalBudgets; ?></div>
        <div class="stat-label">Total Budgets</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">$<?php echo number_format($totalAmount, 2); ?></div>
        <div class="stat-label">Total Amount</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $pendingBudgets; ?></div>
        <div class="stat-label">Pending Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $approvedBudgets; ?></div>
        <div class="stat-label">Approved Requests</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="card-body">
                <a href="budgets/create.php" class="btn btn-primary btn-lg w-100 mb-3">Create New Budget Request</a>
                <a href="budgets/index.php" class="btn btn-success btn-lg w-100">View All Budgets</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Recent Budgets</h4>
            </div>
            <div class="card-body">
                <?php 
                $recentBudgets = $budgetController->getUserBudgets($_SESSION['user_id']);
                $count = 0;
                while($budget = $recentBudgets->fetch(PDO::FETCH_ASSOC) && $count < 5): 
                    $count++;
                ?>
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                        <div>
                            <strong><?php echo $budget['title']; ?></strong>
                            <br>
                            <small class="text-muted">$<?php echo number_format($budget['amount'], 2); ?></small>
                        </div>
                        <span class="status-badge status-<?php echo $budget['status']; ?>">
                            <?php echo ucfirst($budget['status']); ?>
                        </span>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>