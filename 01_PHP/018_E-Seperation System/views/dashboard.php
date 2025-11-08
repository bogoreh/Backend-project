<div class="row">
    <div class="col-12">
        <div class="card border-0 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-3">
                            <i class="fas fa-user-friends text-primary me-3"></i>Employee Separation System
                        </h1>
                        <p class="lead mb-4 text-light">Streamline employee separation processes with our professional management system</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a class="btn btn-primary btn-lg px-4" href="index.php?action=add" role="button">
                                <i class="fas fa-plus me-2"></i>New Separation
                            </a>
                            <a class="btn btn-outline-light btn-lg px-4" href="index.php?action=view" role="button">
                                <i class="fas fa-list me-2"></i>View All Requests
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <i class="fas fa-users fa-8x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-2">TOTAL EMPLOYEES</h6>
                                <h2 class="fw-bold mb-0">156</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-white bg-opacity-20 text-white">+2 this month</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-2">ACTIVE EMPLOYEES</h6>
                                <h2 class="fw-bold mb-0">148</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-white bg-opacity-20 text-white">95% Active</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-dark-50 mb-2">PENDING REQUESTS</h6>
                                <h2 class="fw-bold mb-0">5</h2>
                            </div>
                            <div class="bg-dark bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-dark bg-opacity-20 text-dark">Requires Attention</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-dark h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-dark-50 mb-2">COMPLETED</h6>
                                <h2 class="fw-bold mb-0">3</h2>
                            </div>
                            <div class="bg-dark bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-dark bg-opacity-20 text-dark">This Month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="index.php?action=add" class="card h-100 text-decoration-none">
                                    <div class="card-body text-center hover-effect">
                                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                                        <h6 class="card-title">Add New Separation</h6>
                                        <p class="text-muted small">Create new employee separation request</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="index.php?action=view" class="card h-100 text-decoration-none">
                                    <div class="card-body text-center hover-effect">
                                        <i class="fas fa-tasks fa-3x text-success mb-3"></i>
                                        <h6 class="card-title">Manage Requests</h6>
                                        <p class="text-muted small">View and manage all separation requests</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="card h-100 text-decoration-none">
                                    <div class="card-body text-center hover-effect">
                                        <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                        <h6 class="card-title">View Reports</h6>
                                        <p class="text-muted small">Analytics and separation reports</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-effect {
    transition: all 0.3s ease;
}

.hover-effect:hover {
    transform: translateY(-5px);
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-card) 100%);
}
</style>