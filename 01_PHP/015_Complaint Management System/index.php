<?php
require_once 'includes/functions.php';

// Handle form submission
if ($_POST) {
    if (isset($_POST['add_complaint'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $complaint = $_POST['complaint'];
        
        if (addComplaint($name, $email, $subject, $complaint)) {
            $message = "Complaint submitted successfully!";
            $message_type = "success";
        } else {
            $message = "Error submitting complaint. Please try again.";
            $message_type = "error";
        }
    }
    
    if (isset($_POST['update_status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        
        if (updateComplaintStatus($id, $status)) {
            $message = "Status updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating status. Please try again.";
            $message_type = "error";
        }
    }
}

$complaints = getAllComplaints();
?>

<?php include 'includes/header.php'; ?>

<?php if (isset($message)): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<section class="hero">
    <div class="hero-content">
        <h1>Complaint Management System</h1>
        <p>Efficiently manage and track customer complaints</p>
    </div>
</section>

<section class="complaint-form-section">
    <h2>Submit a Complaint</h2>
    <form method="POST" class="complaint-form">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required>
        </div>
        
        <div class="form-group">
            <label for="complaint">Complaint Details</label>
            <textarea id="complaint" name="complaint" rows="5" required></textarea>
        </div>
        
        <button type="submit" name="add_complaint" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Submit Complaint
        </button>
    </form>
</section>

<section id="complaints" class="complaints-section">
    <h2>Recent Complaints</h2>
    
    <?php if (empty($complaints)): ?>
        <div class="no-complaints">
            <i class="fas fa-inbox"></i>
            <p>No complaints submitted yet.</p>
        </div>
    <?php else: ?>
        <div class="complaints-grid">
            <?php foreach ($complaints as $complaint): ?>
                <div class="complaint-card status-<?php echo strtolower(str_replace(' ', '-', $complaint['status'])); ?>">
                    <div class="complaint-header">
                        <h3><?php echo htmlspecialchars($complaint['subject']); ?></h3>
                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $complaint['status'])); ?>">
                            <?php echo $complaint['status']; ?>
                        </span>
                    </div>
                    
                    <div class="complaint-body">
                        <p><?php echo htmlspecialchars($complaint['complaint']); ?></p>
                    </div>
                    
                    <div class="complaint-footer">
                        <div class="complaint-meta">
                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($complaint['name']); ?></span>
                            <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($complaint['email']); ?></span>
                            <span><i class="fas fa-clock"></i> <?php echo date('M j, Y g:i A', strtotime($complaint['created_at'])); ?></span>
                        </div>
                        
                        <form method="POST" class="status-form">
                            <input type="hidden" name="id" value="<?php echo $complaint['id']; ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="Pending" <?php echo $complaint['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="In Progress" <?php echo $complaint['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Resolved" <?php echo $complaint['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>