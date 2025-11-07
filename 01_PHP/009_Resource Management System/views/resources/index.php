<?php
$content = '
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Resource Management</h2>
    <a href="index.php?action=create" class="btn btn-primary">Add New Resource</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';

if(count($resources) > 0) {
    foreach($resources as $resource) {
        $status_badge = $resource['status'] == 'available' ? 'success' : 
                       ($resource['status'] == 'in-use' ? 'warning' : 'danger');
        
        $content .= '
                    <tr>
                        <td>' . $resource['id'] . '</td>
                        <td>' . htmlspecialchars($resource['name']) . '</td>
                        <td>' . htmlspecialchars($resource['type']) . '</td>
                        <td>' . $resource['quantity'] . '</td>
                        <td><span class="badge bg-' . $status_badge . '">' . ucfirst($resource['status']) . '</span></td>
                        <td>
                            <a href="index.php?action=edit&id=' . $resource['id'] . '" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="index.php?action=delete&id=' . $resource['id'] . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
                        </td>
                    </tr>';
    }
} else {
    $content .= '
                    <tr>
                        <td colspan="6" class="text-center">No resources found</td>
                    </tr>';
}

$content .= '
                </tbody>
            </table>
        </div>
    </div>
</div>';

include 'views/layout.php';
?>