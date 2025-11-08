<?php
include '../layouts/header.php';
require_once '../../controllers/BudgetController.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$budgetController = new BudgetController();

// Get budget ID from URL
if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$budget_id = $_GET['id'];
$budget = $budgetController->getBudget($budget_id);

// Check if budget exists and user has permission to view it
if(!$budget || ($budget['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] === 'user')) {
    header("Location: index.php");
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Budget Request Details</h2>
                <div>
                    <span class="status-badge status-<?php echo $budget['status']; ?> me-2">
                        <?php echo ucfirst($budget['status']); ?>
                    </span>
                    <?php if($budget['user_id'] == $_SESSION['user_id'] && $budget['status'] === 'pending'): ?>
                        <a href="edit.php?id=<?php echo $budget['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label class="detail-label">Title</label>
                            <p class="detail-value"><?php echo htmlspecialchars($budget['title']); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label class="detail-label">Requested Amount</label>
                            <p class="detail-value amount">$<?php echo number_format($budget['amount'], 2); ?></p>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label class="detail-label">Department</label>
                            <p class="detail-value"><?php echo htmlspecialchars($budget['department']); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label class="detail-label">Submitted By</label>
                            <p class="detail-value"><?php echo htmlspecialchars($budget['username']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label class="detail-label">Date Submitted</label>
                            <p class="detail-value"><?php echo date('F j, Y g:i A', strtotime($budget['created_at'])); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-group">
                            <label class="detail-label">Last Updated</label>
                            <p class="detail-value"><?php echo date('F j, Y g:i A', strtotime($budget['updated_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <div class="detail-group">
                    <label class="detail-label">Description</label>
                    <div class="detail-description">
                        <?php echo nl2br(htmlspecialchars($budget['description'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Actions for Managers/Admins -->
        <?php if(($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager') && $budget['status'] === 'pending'): ?>
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Approval Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="?action=approved&id=<?php echo $budget['id']; ?>" 
                           class="btn btn-success btn-lg w-100"
                           onclick="return confirm('Are you sure you want to approve this budget request?')">
                           ✅ Approve Budget
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="?action=rejected&id=<?php echo $budget['id']; ?>" 
                           class="btn btn-danger btn-lg w-100"
                           onclick="return confirm('Are you sure you want to reject this budget request?')">
                           ❌ Reject Budget
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Navigation -->
        <div class="card mt-4">
            <div class="card-body text-center">
                <a href="index.php" class="btn btn-outline-primary me-2">← Back to Budget List</a>
                <?php if($budget['user_id'] == $_SESSION['user_id']): ?>
                    <a href="create.php" class="btn btn-primary me-2">+ New Budget Request</a>
                <?php endif; ?>
                <a href="../dashboard.php" class="btn btn-secondary">Dashboard</a>
            </div>
        </div>
    </div>
</div>

<style>
.detail-group {
    margin-bottom: 1.5rem;
}

.detail-label {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.5rem;
    display: block;
}

.detail-value {
    font-size: 1.1rem;
    margin: 0;
    padding: 0.5rem 0;
}

.detail-value.amount {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
}

.detail-description {
    background: var(--light);
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary);
    line-height: 1.6;
}
</style>

<?php include '../layouts/footer.php'; ?>