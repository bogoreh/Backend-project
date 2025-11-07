<?php
$content = '
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Resource</h4>
            </div>
            <div class="card-body">
                <form action="index.php?action=update" method="POST">
                    <input type="hidden" name="id" value="' . $resource->id . '">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Resource Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="' . htmlspecialchars($resource->name) . '" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="type" name="type" value="' . htmlspecialchars($resource->type) . '" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="' . $resource->quantity . '" min="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available" ' . ($resource->status == 'available' ? 'selected' : '') . '>Available</option>
                            <option value="in-use" ' . ($resource->status == 'in-use' ? 'selected' : '') . '>In Use</option>
                            <option value="maintenance" ' . ($resource->status == 'maintenance' ? 'selected' : '') . '>Maintenance</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">' . htmlspecialchars($resource->description) . '</textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Resource</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>';

include 'views/layout.php';
?>