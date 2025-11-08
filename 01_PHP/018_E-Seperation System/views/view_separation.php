<div class="row">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-dark bg-opacity-25 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-list-alt me-2 text-primary"></i>Separation Requests</h4>
                    <div>
                        <a href="index.php?action=add" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add New
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if(isset($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show m-4 d-flex align-items-center">
                        <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                        <div class="flex-grow-1"><?php echo $message; ?></div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">Employee</th>
                                <th class="border-0">Employee ID</th>
                                <th class="border-0">Department</th>
                                <th class="border-0">Separation Date</th>
                                <th class="border-0">Reason</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $separations->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="align-middle">
                                <td class="fw-semibold"><?php echo $row['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($row['employee_name']); ?></div>
                                            <small class="text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-dark bg-opacity-50 text-light"><?php echo htmlspecialchars($row['employee_id']); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-20 text-primary"><?php echo htmlspecialchars($row['department']); ?></span>
                                </td>
                                <td>
                                    <i class="fas fa-calendar me-1 text-muted"></i>
                                    <?php echo date('M d, Y', strtotime($row['separation_date'])); ?>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                          title="<?php echo htmlspecialchars($row['reason']); ?>">
                                        <?php echo htmlspecialchars($row['reason']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?php 
                                        switch($row['status']) {
                                            case 'Approved': echo 'bg-success'; break;
                                            case 'Rejected': echo 'bg-danger'; break;
                                            case 'Completed': echo 'bg-info text-dark'; break;
                                            default: echo 'bg-warning text-dark';
                                        }
                                        ?> px-3 py-2">
                                        <i class="fas 
                                            <?php 
                                            switch($row['status']) {
                                                case 'Approved': echo 'fa-check'; break;
                                                case 'Rejected': echo 'fa-times'; break;
                                                case 'Completed': echo 'fa-flag-checkered'; break;
                                                default: echo 'fa-clock';
                                            }
                                            ?> me-1">
                                        </i>
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="form-select form-select-sm bg-dark border-dark text-light" 
                                                onchange="this.form.submit()" style="min-width: 120px;">
                                            <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Approved" <?php echo $row['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="Rejected" <?php echo $row['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                            <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>