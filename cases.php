<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['CaseWorker', 'Supervisor', 'Admin'])) { 
    header("Location: dashboard.php?error=unauthorized"); 
    exit(); 
}
require_once 'db.php';

$caseMsg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_case'])) {
    $beneficiary_id = intval($_POST['beneficiary_id'] ?? 0);
    $case_type = trim($_POST['case_type'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $assigned_worker_id = intval($_POST['assigned_worker_id'] ?? 0);
    $date_opened = trim($_POST['date_opened'] ?? date('Y-m-d'));

    if ($beneficiary_id <= 0 || empty($case_type) || empty($priority)) {
        $error = "Please fill in all required case details correctly.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO cases (beneficiary_id, case_type, priority, assigned_worker_id, date_opened, case_status) VALUES (?, ?, ?, ?, ?, 'Open')");
            $stmt->execute([$beneficiary_id, $case_type, $priority, ($assigned_worker_id > 0 ? $assigned_worker_id : null), $date_opened]);
            $caseMsg = "Case opened successfully!";
        } catch (\PDOException $e) {
            $error = "Database error: Unable to initialize case.";
        }
    }
}

$cases = $pdo->query("SELECT c.*, b.first_name, b.last_name, u.username as worker_name FROM cases c JOIN beneficiaries b ON c.beneficiary_id = b.beneficiary_id LEFT JOIN users u ON c.assigned_worker_id = u.user_id ORDER BY c.case_id DESC")->fetchAll();
$beneficiaries = $pdo->query("SELECT * FROM beneficiaries")->fetchAll();
$workers = $pdo->query("SELECT * FROM users WHERE role = 'CaseWorker'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Case Management - NGO System</title>
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
                <li class="breadcrumb-item active" aria-current="page">Case Management</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0"><i class="bi bi-journal-medical text-success me-2"></i> Case Management</h3>
                <p class="text-muted small mb-0">Initialize cases, assign caseworkers, and monitor outcomes[cite: 1].</p>
            </div>
            <a href="beneficiaries.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-person-plus me-1"></i> Register Beneficiary</a>
        </div>

        <?php if($caseMsg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($caseMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Create Case Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-success"><i class="bi bi-folder-plus me-2"></i> Open New Case</h5>
                <form method="POST" class="row g-3 needs-validation" novalidate>
                    <input type="hidden" name="create_case" value="1">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Beneficiary *</label>
                        <select name="beneficiary_id" class="form-select" required>
                            <option value="">Select Beneficiary...</option>
                            <?php foreach($beneficiaries as $b): ?>
                                <option value="<?= $b['beneficiary_id']; ?>"><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Case Type *</label>
                        <input type="text" name="case_type" class="form-control" placeholder="e.g. Child Protection, Counseling" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Priority Level *</label>
                        <select name="priority" class="form-select" required>
                            <option>Low</option>
                            <option selected>Medium</option>
                            <option>High</option>
                            <option>Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Assign Caseworker</label>
                        <select name="assigned_worker_id" class="form-select">
                            <option value="">Unassigned</option>
                            <?php foreach($workers as $w): ?>
                                <option value="<?= $w['user_id']; ?>"><?= htmlspecialchars($w['username']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Date Opened *</label>
                        <input type="date" name="date_opened" value="<?= date('Y-m-d'); ?>" class="form-control" required>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check2-circle me-1"></i> Initialize Case Record</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cases Table -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-table me-2"></i> Active Case Registry</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Case ID</th>
                                <th>Beneficiary</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Assigned Worker</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cases as $c): ?>
                            <tr>
                                <td class="fw-semibold">#<?= $c['case_id']; ?></td>
                                <td><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></td>
                                <td><?= htmlspecialchars($c['case_type']); ?></td>
                                <td><span class="badge bg-<?= $c['priority']=='Urgent'?'danger':($c['priority']=='High'?'warning text-dark':'secondary'); ?>"><?= htmlspecialchars($c['priority']); ?></span></td>
                                <td><?= htmlspecialchars($c['worker_name'] ?? 'Unassigned'); ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($c['case_status']); ?></span></td>
                                <td>
                                    <a href="case_details.php?id=<?= $c['case_id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-timeline me-1"></i> View Timeline
                                    </a>
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