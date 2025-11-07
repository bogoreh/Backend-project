<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-4 text-primary"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p class="lead">Welcome to Student Project Management System</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card text-white mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4><?php echo count(getAllStudents($pdo)); ?></h4>
                        <p>Total Students</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4><?php echo count(getAllProjects($pdo)); ?></h4>
                        <p>Total Projects</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4><?php echo count(getAllAllocations($pdo)); ?></h4>
                        <p>Project Allocations</p>
                    </div>
                    <div class="col-4 text-end">
                        <i class="fas fa-link"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Allocations</h5>
            </div>
            <div class="card-body">
                <?php
                $allocations = getAllAllocations($pdo);
                if (count($allocations) > 0):
                    $recentAllocations = array_slice($allocations, 0, 5);
                ?>
                    <div class="list-group">
                        <?php foreach($recentAllocations as $allocation): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($allocation['student_name']); ?></h6>
                                <small class="text-muted"><?php echo date('M d, Y', strtotime($allocation['allocated_at'])); ?></small>
                            </div>
                            <p class="mb-1">Project: <?php echo htmlspecialchars($allocation['project_title']); ?></p>
                            <span class="badge bg-<?php echo $allocation['status'] == 'approved' ? 'success' : ($allocation['status'] == 'rejected' ? 'danger' : 'warning'); ?>">
                                <?php echo ucfirst($allocation['status']); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No allocations found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Quick Actions</h5>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-6 mb-3">
                        <a href="students.php?action=add" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                            Add Student
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="projects.php?action=add" class="btn btn-outline-success btn-lg w-100">
                            <i class="fas fa-project-diagram fa-2x mb-2"></i><br>
                            Add Project
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="allocations.php?action=add" class="btn btn-outline-info btn-lg w-100">
                            <i class="fas fa-link fa-2x mb-2"></i><br>
                            Allocate Project
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="students.php" class="btn btn-outline-warning btn-lg w-100">
                            <i class="fas fa-list fa-2x mb-2"></i><br>
                            View All
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>