<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['CaseWorker', 'Supervisor', 'Admin'])) { 
    header("Location: dashboard.php?error=unauthorized"); 
    exit(); 
}
require_once 'db.php';

$case_id = $_GET['id'] ?? null;
if(!$case_id) { header("Location: cases.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add_assessment'])) {
        $stmt = $pdo->prepare("INSERT INTO assessments (case_id, assessed_by, assessment_date, identified_needs, risk_level, observations, recommendations) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$case_id, $_SESSION['user_id'], $_POST['assessment_date'], $_POST['identified_needs'], $_POST['risk_level'], $_POST['observations'], $_POST['recommendations']]);
    } elseif(isset($_POST['add_intervention'])) {
        $stmt = $pdo->prepare("INSERT INTO interventions (case_id, service_type, description, service_provider, intervention_date, outcome) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$case_id, $_POST['service_type'], $_POST['description'], $_POST['service_provider'], $_POST['intervention_date'], $_POST['outcome']]);
    }
    header("Location: case_details.php?id=$case_id");
    exit();
}

$case = $pdo->prepare("SELECT c.*, b.first_name, b.last_name FROM cases c JOIN beneficiaries b ON c.beneficiary_id = b.beneficiary_id WHERE c.case_id = ?");
$case->execute([$case_id]);
$caseData = $case->fetch();

$assessments = $pdo->prepare("SELECT * FROM assessments WHERE case_id = ?"); $assessments->execute([$case_id]);
$interventions = $pdo->prepare("SELECT * FROM interventions WHERE case_id = ?"); $interventions->execute([$case_id]);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Case Timeline - #<?= $case_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4 fade-in">
        <a href="cases.php" class="btn btn-outline-secondary mb-4 shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back to Cases</a>
        
        <div class="card shadow-sm mb-4 border-start border-primary border-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-1"><i class="bi bi-person-badge text-primary me-2"></i> Case Profile #<?= $caseData['case_id']; ?>: <?= htmlspecialchars($caseData['first_name'] . ' ' . $caseData['last_name']); ?></h3>
                <p class="text-muted mb-0">
                    <strong>Type:</strong> <?= $caseData['case_type']; ?> &bull; 
                    <strong>Status:</strong> <span class="badge bg-secondary"><?= $caseData['case_status']; ?></span> &bull; 
                    <strong>Priority:</strong> <span class="badge bg-warning text-dark"><?= $caseData['priority']; ?></span>
                </p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-primary mb-3"><i class="bi bi-clipboard2-pulse me-2"></i> Add Needs & Risk Assessment</h5>
                        <form method="POST">
                            <input type="hidden" name="add_assessment" value="1">
                            <div class="mb-3"><label class="form-label small fw-semibold">Assessment Date</label><input type="date" name="assessment_date" value="<?= date('Y-m-d'); ?>" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Identified Needs</label><textarea name="identified_needs" class="form-control" rows="2" required></textarea></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Risk Level</label><select name="risk_level" class="form-select"><option>Low</option><option>Moderate</option><option>High</option><option>Severe</option></select></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Observations</label><textarea name="observations" class="form-control" rows="2"></textarea></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Recommendations</label><textarea name="recommendations" class="form-control" rows="2"></textarea></div>
                            <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-save me-1"></i> Save Assessment</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-success mb-3"><i class="bi bi-heart-pulse me-2"></i> Record Intervention / Service</h5>
                        <form method="POST">
                            <input type="hidden" name="add_intervention" value="1">
                            <div class="mb-3"><label class="form-label small fw-semibold">Service Type</label><input type="text" name="service_type" class="form-control" placeholder="e.g. Counseling, Education Support" required></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Description</label><textarea name="description" class="form-control" rows="2" required></textarea></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Service Provider</label><input type="text" name="service_provider" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Intervention Date</label><input type="date" name="intervention_date" value="<?= date('Y-m-d'); ?>" class="form-control" required></div>
                            <div class="mb-3"><label class="form-label small fw-semibold">Outcome</label><textarea name="outcome" class="form-control" rows="2"></textarea></div>
                            <button type="submit" class="btn btn-success btn-sm px-3"><i class="bi bi-save me-1"></i> Save Intervention</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrated Timeline Feed -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold mb-4"><i class="bi bi-clock-history me-2"></i> Integrated Case Timeline & History</h4>
                
                <h5 class="text-primary fs-6 fw-bold text-uppercase border-bottom pb-2">Assessments</h5>
                <ul class="list-unstyled mb-4">
                    <?php while($a = $assessments->fetch()): ?>
                        <li class="p-3 mb-2 bg-light rounded shadow-sm">
                            <span class="badge bg-primary mb-1"><?= $a['assessment_date']; ?></span> &bull; <strong>Risk:</strong> <?= $a['risk_level']; ?>
                            <p class="mb-0 mt-1 small text-muted"><strong>Needs:</strong> <?= htmlspecialchars($a['identified_needs']); ?></p>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <h5 class="text-success fs-6 fw-bold text-uppercase border-bottom pb-2">Interventions</h5>
                <ul class="list-unstyled">
                    <?php while($i = $interventions->fetch()): ?>
                        <li class="p-3 mb-2 bg-light rounded shadow-sm">
                            <span class="badge bg-success mb-1"><?= $i['intervention_date']; ?></span> &bull; <strong><?= htmlspecialchars($i['service_type']); ?></strong> via <?= htmlspecialchars($i['service_provider']); ?>
                            <p class="mb-0 mt-1 small text-muted"><strong>Outcome:</strong> <?= htmlspecialchars($i['outcome'] ?: 'Pending feedback'); ?></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>