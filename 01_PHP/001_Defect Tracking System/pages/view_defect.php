<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
$defect = getDefectById($id);
?>

<h2>Defect Details</h2>

<div class="card">
    <div class="card-body">
        <h4 class="card-title"><?php echo htmlspecialchars($defect['title']); ?></h4>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Status:</strong> <?php echo getStatusBadge($defect['status']); ?>
            </div>
            <div class="col-md-6">
                <strong>Priority:</strong> <?php echo getPriorityBadge($defect['priority']); ?>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Assigned To:</strong> <?php echo htmlspecialchars($defect['assigned_to']); ?>
            </div>
            <div class="col-md-6">
                <strong>Created By:</strong> <?php echo htmlspecialchars($defect['created_by']); ?>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Created:</strong> <?php echo date('F j, Y, g:i a', strtotime($defect['created_at'])); ?>
            </div>
            <div class="col-md-6">
                <strong>Last Updated:</strong> <?php echo date('F j, Y, g:i a', strtotime($defect['updated_at'])); ?>
            </div>
        </div>
        
        <div class="mb-3">
            <strong>Description:</strong>
            <p class="mt-2"><?php echo nl2br(htmlspecialchars($defect['description'])); ?></p>
        </div>
        
        <a href="edit_defect.php?id=<?php echo $defect['id']; ?>" class="btn btn-warning">Edit</a>
        <a href="defects.php" class="btn btn-secondary">Back to List</a>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>