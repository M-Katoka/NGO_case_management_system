<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';

$beneficiariesCount = $pdo->query("SELECT COUNT(*) FROM beneficiaries")->fetchColumn();
$activeCasesCount = $pdo->query("SELECT COUNT(*) FROM cases WHERE case_status != 'Closed'")->fetchColumn();
$pendingReferrals = $pdo->query("SELECT COUNT(*) FROM referrals WHERE referral_status = 'Pending'")->fetchColumn();
$upcomingFollowUps = $pdo->query("SELECT COUNT(*) FROM follow_ups WHERE next_follow_up_date >= CURDATE()")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - NGO Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container py-3 fade-in">
        <?php if(isset($_GET['error']) && $_GET['error'] === 'unauthorized'): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Access Denied: You do not have permissions for that module.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Executive Banner -->
        <div class="card bg-primary text-white p-4 mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-1">Welcome back, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
                    <p class="text-white-50 mb-0">NGO Case Management Information System &bull; Role: <span class="badge bg-light text-primary"><?= $_SESSION['role']; ?></span></p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white bg-opacity-10 text-white p-2 border border-light border-opacity-25"><i class="bi bi-calendar-event me-1"></i> <?= date('l, F j, Y'); ?></span>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card stat-primary p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold small mb-1">Beneficiaries</h6>
                            <h2 class="fw-bold mb-0 text-primary"><?= $beneficiariesCount; ?></h2>
                        </div>
                        <div class="fs-1 text-primary opacity-25"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card stat-success p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold small mb-1">Active Cases</h6>
                            <h2 class="fw-bold mb-0 text-success"><?= $activeCasesCount; ?></h2>
                        </div>
                        <div class="fs-1 text-success opacity-25"><i class="bi bi-folder2-open"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card stat-warning p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold small mb-1">Pending Referrals</h6>
                            <h2 class="fw-bold mb-0 text-warning"><?= $pendingReferrals; ?></h2>
                        </div>
                        <div class="fs-1 text-warning opacity-25"><i class="bi bi-arrow-up-right-circle"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card stat-info p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold small mb-1">Follow-ups Due</h6>
                            <h2 class="fw-bold mb-0 text-info"><?= $upcomingFollowUps; ?></h2>
                        </div>
                        <div class="fs-1 text-info opacity-25"><i class="bi bi-calendar-check"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Workflow Shortcuts -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i> Quick Workflow Shortcuts</h5>
                <div class="row g-3">
                    <?php if(in_array($_SESSION['role'], ['CaseWorker', 'Supervisor', 'Admin'])): ?>
                    <div class="col-md-4">
                        <a href="beneficiaries.php" class="card h-100 text-decoration-none p-3 border-start border-primary border-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3"><i class="bi bi-person-plus-fill fs-4"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-body">Register Beneficiary</h6>
                                    <span class="text-muted small">Capture profile & location[cite: 1].</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="cases.php" class="card h-100 text-decoration-none p-3 border-start border-success border-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3"><i class="bi bi-folder-plus fs-4"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-body">Create New Case</h6>
                                    <span class="text-muted small">Assign caseworkers & priority[cite: 1].</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <a href="reports.php" class="card h-100 text-decoration-none p-3 border-start border-secondary border-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary p-3 rounded-circle me-3"><i class="bi bi-bar-chart-line-fill fs-4"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-body">Generate Reports</h6>
                                    <span class="text-muted small">View & print summaries[cite: 1].</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>