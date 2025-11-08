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

// Check if budget exists and user owns it
if(!$budget || $budget['user_id'] != $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}

// Check if budget can be edited (only pending budgets can be edited)
if($budget['status'] !== 'pending') {
    $_SESSION['error'] = "Only pending budget requests can be edited.";
    header("Location: show.php?id=" . $budget_id);
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>Edit Budget Request</h2>
                <p class="mb-0">Update your budget request details</p>
            </div>
            <div class="card-body">
                <form method="POST" action="../../controllers/BudgetController.php">
                    <input type="hidden" name="budget_id" value="<?php echo $budget['id']; ?>">
                    
                    <div class="form-group">
                        <label for="title">Budget Title *</label>
                        <input type="text" id="title" name="title" class="form-control" required 
                               value="<?php echo htmlspecialchars($budget['title']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <option value="Marketing" <?php echo $budget['department'] == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                            <option value="Sales" <?php echo $budget['department'] == 'Sales' ? 'selected' : ''; ?>>Sales</option>
                            <option value="IT" <?php echo $budget['department'] == 'IT' ? 'selected' : ''; ?>>IT</option>
                            <option value="HR" <?php echo $budget['department'] == 'HR' ? 'selected' : ''; ?>>Human Resources</option>
                            <option value="Finance" <?php echo $budget['department'] == 'Finance' ? 'selected' : ''; ?>>Finance</option>
                            <option value="Operations" <?php echo $budget['department'] == 'Operations' ? 'selected' : ''; ?>>Operations</option>
                            <option value="R&D" <?php echo $budget['department'] == 'R&D' ? 'selected' : ''; ?>>Research & Development</option>
                            <option value="Other" <?php echo $budget['department'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount Requested ($) *</label>
                        <input type="number" id="amount" name="amount" class="form-control" 
                               step="0.01" min="0" required 
                               value="<?php echo $budget['amount']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control" 
                                  rows="5" required><?php echo htmlspecialchars($budget['description']); ?></textarea>
                    </div>

                    <div class="alert alert-info">
                        <strong>Note:</strong> Editing this budget request will reset its approval status to "Pending" and require re-approval.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <a href="show.php?id=<?php echo $budget['id']; ?>" class="btn btn-secondary w-100">Cancel</a>
                        </div>
                        <div class="col-md-4">
                            <a href="index.php" class="btn btn-outline-primary w-100">Back to List</a>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="update_budget" class="btn btn-primary w-100">
                                Update Budget Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-warning">
                <h5>Budget Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Current Status:</strong>
                        <span class="status-badge status-<?php echo $budget['status']; ?>">
                            <?php echo ucfirst($budget['status']); ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong> 
                        <?php echo date('F j, Y g:i A', strtotime($budget['created_at'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize character counter
document.addEventListener('DOMContentLoaded', function() {
    const description = document.getElementById('description');
    const counter = document.createElement('small');
    counter.id = 'charCounter';
    counter.className = 'text-muted float-end';
    counter.textContent = description.value.length + ' characters';
    document.querySelector('label[for="description"]').appendChild(counter);

    description.addEventListener('input', function(e) {
        counter.textContent = e.target.value.length + ' characters';
    });
});
</script>

<?php include '../layouts/footer.php'; ?>