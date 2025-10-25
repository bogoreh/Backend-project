<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';
?>
<h2>All Defects</h2>

<?php
if(isset($_GET['delete']) && $_GET['delete'] == 'success') {
    echo '<div class="alert alert-success">Defect deleted successfully!</div>';
}

if(isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<div class="alert alert-success">Defect updated successfully!</div>';
}
?>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Assigned To</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = getAllDefects();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['title']}</td>";
            echo "<td>" . getStatusBadge($row['status']) . "</td>";
            echo "<td>" . getPriorityBadge($row['priority']) . "</td>";
            echo "<td>{$row['assigned_to']}</td>";
            echo "<td>" . date('M j, Y', strtotime($row['created_at'])) . "</td>";
            echo "<td>";
            echo "<a href='view_defect.php?id={$row['id']}' class='btn btn-sm btn-info'>View</a> ";
            echo "<a href='edit_defect.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a> ";
            echo "<a href='../includes/delete_defect.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<a href="add_defect.php" class="btn btn-primary">Add New Defect</a>

<?php
require_once '../includes/footer.php';
?>