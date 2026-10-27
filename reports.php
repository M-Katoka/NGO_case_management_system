<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Management', 'Supervisor', 'Admin'])) { 
    header("Location: dashboard.php?error=unauthorized"); 
    exit(); 
}
require_once 'db.php';

$caseSummary = $pdo->query("SELECT case_status, COUNT(*) as total FROM cases GROUP BY case_status")->fetchAll();
$prioritySummary = $pdo->query("SELECT priority, COUNT(*) as total FROM cases GROUP BY priority")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Reports & Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4 fade-in">
        <a href="dashboard.php" class="btn btn-outline-secondary mb-4 shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-bar-chart-line-fill text-secondary me-2"></i> Program Reports & Statistics</h2>
                <p class="text-muted mb-0">Aggregated metrics and statistical summaries for organizational decision-making.</p>
            </div>
            <button onclick="window.print();" class="btn btn-dark shadow-sm"><i class="bi bi-printer me-1"></i> Print Report</button>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i> Cases by Status</h5>
                        <table class="table table-hover align-middle mt-2">
                            <thead><tr><th>Status</th><th>Total Cases</th></tr></thead>
                            <tbody>
                                <?php foreach($caseSummary as $cs): ?>
                                <tr><td><span class="badge bg-light text-dark border"><?= $cs['case_status']; ?></span></td><td class="fw-bold"><?= $cs['total']; ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i> Cases by Priority</h5>
                        <table class="table table-hover align-middle mt-2">
                            <thead><tr><th>Priority</th><th>Total Cases</th></tr></thead>
                            <tbody>
                                <?php foreach($prioritySummary as $ps): ?>
                                <tr><td><span class="badge bg-secondary"><?= $ps['priority']; ?></span></td><td class="fw-bold"><?= $ps['total']; ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>