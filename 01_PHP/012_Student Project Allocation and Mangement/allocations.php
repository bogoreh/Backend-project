<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 text-primary"><i class="fas fa-link"></i> Project Allocations</h1>
    <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Allocate Project</a>
</div>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    // Allocate project form
    if ($_POST) {
        $student_id = $_POST['student_id'];
        $project_id = $_POST['project_id'];
        
        if (allocateProject($pdo, $student_id, $project_id)) {
            echo '<div class="alert alert-success">Project allocated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error allocating project. Student might already have a project.</div>';
        }
    }
    
    $students = getAllStudents($pdo);
    $projects = getAllProjects($pdo);
    ?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-link"></i> Allocate Project to Student</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_id" class="form-label">Select Student</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">Choose a student...</option>
                            <?php foreach($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>">
                                <?php echo htmlspecialchars($student['name'] . ' (' . $student['student_id'] . ')'); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="project_id" class="form-label">Select Project</label>
                        <select class="form-select" id="project_id" name="project_id" required>
                            <option value="">Choose a project...</option>
                            <?php foreach($projects as $project): ?>
                            <option value="<?php echo $project['id']; ?>">
                                <?php echo htmlspecialchars($project['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Allocate Project</button>
                <a href="allocations.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    <?php
} else {
    // Display allocations list
    $allocations = getAllAllocations($pdo);
    ?>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Allocations List</h5>
        </div>
        <div class="card-body">
            <?php if (count($allocations) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Project</th>
                            <th>Allocated Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allocations as $allocation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($allocation['student_name']); ?></td>
                            <td><strong><?php echo htmlspecialchars($allocation['student_id']); ?></strong></td>
                            <td><?php echo htmlspecialchars($allocation['project_title']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($allocation['allocated_at'])); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $allocation['status'] == 'approved' ? 'success' : ($allocation['status'] == 'rejected' ? 'danger' : 'warning'); ?>">
                                    <?php echo ucfirst($allocation['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-link fa-3x text-muted mb-3"></i>
                <p class="text-muted">No allocations found. <a href="?action=add">Create the first allocation</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>

<?php include 'includes/footer.php'; ?>