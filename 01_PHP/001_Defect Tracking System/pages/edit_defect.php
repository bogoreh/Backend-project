<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
$defect = getDefectById($id);

if($_POST) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $assigned_to = $_POST['assigned_to'];
    
    if(updateDefect($id, $title, $description, $status, $priority, $assigned_to)) {
        header("Location: defects.php?update=success");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Unable to update defect.</div>";
    }
}
?>

<h2>Edit Defect</h2>

<form method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($defect['title']); ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($defect['description']); ?></textarea>
    </div>
    
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status" required>
            <option value="Open" <?php echo $defect['status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
            <option value="In Progress" <?php echo $defect['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
            <option value="Resolved" <?php echo $defect['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
            <option value="Closed" <?php echo $defect['status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="priority" class="form-label">Priority</label>
        <select class="form-control" id="priority" name="priority" required>
            <option value="Low" <?php echo $defect['priority'] == 'Low' ? 'selected' : ''; ?>>Low</option>
            <option value="Medium" <?php echo $defect['priority'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
            <option value="High" <?php echo $defect['priority'] == 'High' ? 'selected' : ''; ?>>High</option>
            <option value="Critical" <?php echo $defect['priority'] == 'Critical' ? 'selected' : ''; ?>>Critical</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="assigned_to" class="form-label">Assigned To</label>
        <input type="text" class="form-control" id="assigned_to" name="assigned_to" value="<?php echo htmlspecialchars($defect['assigned_to']); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Update Defect</button>
    <a href="defects.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
require_once '../includes/footer.php';
?>