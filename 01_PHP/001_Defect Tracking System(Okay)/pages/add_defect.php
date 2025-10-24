<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';

if($_POST) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = "Current User"; // In real app, get from session
    
    if(addDefect($title, $description, $priority, $assigned_to, $created_by)) {
        header("Location: defects.php?add=success");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Unable to add defect.</div>";
    }
}
?>

<h2>Add New Defect</h2>

<form method="post">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>
    
    <div class="mb-3">
        <label for="priority" class="form-label">Priority</label>
        <select class="form-control" id="priority" name="priority" required>
            <option value="Low">Low</option>
            <option value="Medium" selected>Medium</option>
            <option value="High">High</option>
            <option value="Critical">Critical</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="assigned_to" class="form-label">Assigned To</label>
        <input type="text" class="form-control" id="assigned_to" name="assigned_to" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Add Defect</button>
    <a href="defects.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
require_once '../includes/footer.php';
?>