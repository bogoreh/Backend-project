<?php
include '../layouts/header.php';
require_once '../../controllers/BudgetController.php';

$budgetController = new BudgetController();

if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager') {
    $budgets = $budgetController->getAllBudgets();
} else {
    $budgets = $budgetController->getUserBudgets($_SESSION['user_id']);
}
?>

<div class="card">
    <div class="card-header">
        <h2>Budget Requests</h2>
        <a href="create.php" class="btn btn-primary">New Budget Request</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Amount</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Date</th>
                    <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager'): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $budgets->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td>$<?php echo number_format($row['amount'], 2); ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                    <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager'): ?>
                    <td class="budget-actions">
                        <?php if($row['status'] === 'pending'): ?>
                            <a href="?action=approved&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="?action=rejected&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>