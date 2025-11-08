<?php
include '../layouts/header.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>Create New Budget Request</h2>
                <p class="mb-0">Fill in the details below to submit a budget request</p>
            </div>
            <div class="card-body">
                <form method="POST" action="../../controllers/BudgetController.php">
                    <div class="form-group">
                        <label for="title">Budget Title *</label>
                        <input type="text" id="title" name="title" class="form-control" required 
                               placeholder="Enter a descriptive title for your budget request">
                    </div>

                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Sales">Sales</option>
                            <option value="IT">IT</option>
                            <option value="HR">Human Resources</option>
                            <option value="Finance">Finance</option>
                            <option value="Operations">Operations</option>
                            <option value="R&D">Research & Development</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount Requested ($) *</label>
                        <input type="number" id="amount" name="amount" class="form-control" 
                               step="0.01" min="0" required placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control" 
                                  rows="5" required placeholder="Provide detailed information about what this budget will be used for..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a href="index.php" class="btn btn-secondary w-100">Cancel</a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="create_budget" class="btn btn-primary w-100">
                                Submit Budget Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Budget Request Guidelines</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li>✅ Provide clear and detailed descriptions</li>
                    <li>✅ Specify the exact amount needed</li>
                    <li>✅ Choose the appropriate department</li>
                    <li>✅ Include timeline information in description if applicable</li>
                    <li>❌ Don't submit incomplete information</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Real-time amount formatting
document.getElementById('amount').addEventListener('input', function(e) {
    let value = e.target.value;
    if (value && !isNaN(value)) {
        e.target.value = parseFloat(value).toFixed(2);
    }
});

// Character counter for description
document.getElementById('description').addEventListener('input', function(e) {
    const length = e.target.value.length;
    const counter = document.getElementById('charCounter') || createCounter();
    counter.textContent = `${length} characters`;
});

function createCounter() {
    const counter = document.createElement('small');
    counter.id = 'charCounter';
    counter.className = 'text-muted float-end';
    counter.textContent = '0 characters';
    document.querySelector('label[for="description"]').appendChild(counter);
    return counter;
}
</script>

<?php include '../layouts/footer.php'; ?>