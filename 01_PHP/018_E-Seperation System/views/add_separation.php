<div class="row justify-content-center">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-dark bg-opacity-25 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-user-minus me-2 text-primary"></i>New Separation Request</h4>
                    <a href="index.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if(isset($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show d-flex align-items-center">
                        <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                        <div class="flex-grow-1"><?php echo $message; ?></div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=add" class="needs-validation" novalidate>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="employee_name" class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i>Employee Name
                            </label>
                            <input type="text" class="form-control form-control-lg" id="employee_name" name="employee_name" required placeholder="Enter employee full name">
                            <div class="invalid-feedback">Please enter employee name.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-semibold">
                                <i class="fas fa-id-card me-1 text-primary"></i>Employee ID
                            </label>
                            <input type="text" class="form-control form-control-lg" id="employee_id" name="employee_id" required placeholder="Enter employee ID">
                            <div class="invalid-feedback">Please enter employee ID.</div>
                        </div>
                        
                        <div class="col-12">
                            <label for="department" class="form-label fw-semibold">
                                <i class="fas fa-building me-1 text-primary"></i>Department
                            </label>
                            <select class="form-select form-select-lg" id="department" name="department" required>
                                <option value="">Select Department</option>
                                <option value="IT">IT Department</option>
                                <option value="HR">Human Resources</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Operations">Operations</option>
                                <option value="Sales">Sales</option>
                                <option value="Support">Customer Support</option>
                            </select>
                            <div class="invalid-feedback">Please select a department.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="separation_date" class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i>Separation Date
                            </label>
                            <input type="date" class="form-control form-control-lg" id="separation_date" name="separation_date" required>
                            <div class="invalid-feedback">Please select separation date.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">
                                <i class="fas fa-tasks me-1 text-primary"></i>Status
                            </label>
                            <select class="form-select form-select-lg" id="status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Completed">Completed</option>
                            </select>
                            <div class="invalid-feedback">Please select status.</div>
                        </div>
                        
                        <div class="col-12">
                            <label for="reason" class="form-label fw-semibold">
                                <i class="fas fa-comment-alt me-1 text-primary"></i>Reason for Separation
                            </label>
                            <textarea class="form-control form-control-lg" id="reason" name="reason" rows="5" required placeholder="Provide detailed reason for separation..."></textarea>
                            <div class="invalid-feedback">Please provide reason for separation.</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-outline-light btn-lg px-4">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Request
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>