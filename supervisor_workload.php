<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Supervisor', 'Admin'])) { 
    header("Location: dashboard.php?error=unauthorized"); 
    exit(); 
}
require_once 'db.php';

$workload = $pdo->query("
    SELECT u.user_id, u.username, u.email, 
           COUNT(c.case_id) as total_assigned_cases,
           SUM(CASE WHEN c.case_status != 'Closed' THEN 1 ELSE 0 END) as active_cases
    FROM users u
    LEFT JOIN cases c ON u.user_id = c.assigned_worker_id
    WHERE u.role = 'CaseWorker'
    GROUP BY u.user_id, u.username, u.email
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Caseworker Workload Monitoring - NGO System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container py-3 fade-in">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Workload Monitoring</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0"><i class="bi bi-bar-chart-steps text-success me-2"></i> Caseworker Workload Monitoring</h3>
                <p class="text-muted small mb-0">Supervisory oversight view to balance active case allocation among caseworkers.</p>
            </div>
            <button onclick="window.print();" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i> Print Report</button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-people me-2"></i> Active Case Distribution</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Worker ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Active Cases</th>
                                <th>Total Assigned Cases</th>
                                <th>Workload Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($workload as $w): ?>
                            <tr>
                                <td class="fw-semibold">#<?= $w['user_id']; ?></td>
                                <td><?= htmlspecialchars($w['username']); ?></td>
                                <td><?= htmlspecialchars($w['email']); ?></td>
                                <td><span class="badge bg-primary"><?= $w['active_cases']; ?> Active</span></td>
                                <td class="fw-bold"><?= $w['total_assigned_cases']; ?></td>
                                <td>
                                    <?php if($w['active_cases'] > 5): ?>
                                        <span class="badge bg-danger">High Load</span>
                                    <?php elseif($w['active_cases'] > 2): ?>
                                        <span class="badge bg-warning text-dark">Moderate</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Optimal</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>