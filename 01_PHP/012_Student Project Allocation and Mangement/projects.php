<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 text-primary"><i class="fas fa-tasks"></i> Projects Management</h1>
    <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Project</a>
</div>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    // Add project form
    if ($_POST) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $supervisor = $_POST['supervisor'];
        $max_students = $_POST['max_students'];
        
        if (addProject($pdo, $title, $description, $supervisor, $max_students)) {
            echo '<div class="alert alert-success">Project added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding project.</div>';
        }
    }
    ?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-project-diagram"></i> Add New Project</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Project Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="supervisor" class="form-label">Supervisor</label>
                        <input type="text" class="form-control" id="supervisor" name="supervisor" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="max_students" class="form-label">Max Students</label>
                        <input type="number" class="form-control" id="max_students" name="max_students" value="1" min="1" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Project</button>
                <a href="projects.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    <?php
} else {
    // Display projects list
    $projects = getAllProjects($pdo);
    ?>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Projects List</h5>
        </div>
        <div class="card-body">
            <?php if (count($projects) > 0): ?>
            <div class="row">
                <?php foreach($projects as $project): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($project['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($project['description']); ?></p>
                            <div class="project-details">
                                <small class="text-muted">
                                    <i class="fas fa-user-tie"></i> Supervisor: <?php echo htmlspecialchars($project['supervisor']); ?><br>
                                    <i class="fas fa-users"></i> Max Students: <?php echo $project['max_students']; ?>
                                </small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Created: <?php echo date('M d, Y', strtotime($project['created_at'])); ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                <p class="text-muted">No projects found. <a href="?action=add">Add the first project</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>

<?php include 'includes/footer.php'; ?>