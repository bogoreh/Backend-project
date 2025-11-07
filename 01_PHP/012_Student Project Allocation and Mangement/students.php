<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 text-primary"><i class="fas fa-users"></i> Students Management</h1>
    <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Student</a>
</div>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    // Add student form
    if ($_POST) {
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $department = $_POST['department'];
        
        if (addStudent($pdo, $student_id, $name, $email, $department)) {
            echo '<div class="alert alert-success">Student added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding student. Student ID might already exist.</div>';
        }
    }
    ?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> Add New Student</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Student</button>
                <a href="students.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    <?php
} else {
    // Display students list
    $students = getAllStudents($pdo);
    ?>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Students List</h5>
        </div>
        <div class="card-body">
            <?php if (count($students) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($students as $student): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($student['student_id']); ?></strong></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['department']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">No students found. <a href="?action=add">Add the first student</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>

<?php include 'includes/footer.php'; ?>