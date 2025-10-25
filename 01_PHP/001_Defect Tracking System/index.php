<?php
require_once 'includes/header.php';
?>
<div class="jumbotron bg-light p-5 rounded">
    <h1 class="display-4">Welcome to Defect Tracking System</h1>
    <p class="lead">A simple system to track and manage software defects efficiently.</p>
    <hr class="my-4">
    <p>Manage bugs, issues, and feature requests in one place.</p>
    <a class="btn btn-primary btn-lg" href="pages/defects.php" role="button">View All Defects</a>
    <a class="btn btn-success btn-lg" href="pages/add_defect.php" role="button">Report New Defect</a>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Track Issues</h5>
                <p class="card-text">Keep track of all software issues and bugs in one centralized location.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Assign Tasks</h5>
                <p class="card-text">Assign defects to team members and track progress efficiently.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Priority Management</h5>
                <p class="card-text">Set priorities and status to manage workflow effectively.</p>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'includes/footer.php';
?>