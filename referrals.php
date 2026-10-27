<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['CaseWorker', 'Supervisor', 'Admin'])) { 
    header("Location: dashboard.php?error=unauthorized"); 
    exit(); 
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_referral'])) {
    $stmt = $pdo->prepare("UPDATE referrals SET referral_status = ?, completion_info = ? WHERE referral_id = ?");
    $stmt->execute([$_POST['referral_status'], $_POST['completion_info'], $_POST['referral_id']]);
    header("Location: referrals.php");
    exit();
}

$referrals = $pdo->query("SELECT r.*, c.case_type, b.first_name, b.last_name FROM referrals r JOIN cases c ON r.case_id = c.case_id JOIN beneficiaries b ON c.beneficiary_id = b.beneficiary_id ORDER BY r.referral_id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Referral Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4 fade-in">
        <a href="dashboard.php" class="btn btn-outline-secondary mb-4 shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
        
        <div class="mb-4">
            <h2><i class="bi bi-share-fill text-warning me-2"></i> Organization Referral Tracking</h2>
            <p class="text-muted mb-0">Monitor external partner referrals, status updates, and completion milestones[cite: 1, 2, 3, 4].</p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3"><i class="bi bi-table me-2"></i> All External Referrals</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mt-2">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Beneficiary</th>
                                <th>Referred To</th>
                                <th>Service Required</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($referrals as $ref): ?>
                            <tr>
                                <td class="fw-semibold">#<?= $ref['referral_id']; ?></td>
                                <td><?= htmlspecialchars($ref['first_name'] . ' ' . $ref['last_name']); ?></td>
                                <td><?= htmlspecialchars($ref['referred_to_organization']); ?></td>
                                <td><?= htmlspecialchars($ref['service_required']); ?></td>
                                <td><?= $ref['referral_date']; ?></td>
                                <td>
                                    <span class="badge bg-<?= $ref['referral_status']=='Completed'?'success':($ref['referral_status']=='Pending'?'warning text-dark':'danger'); ?>">
                                        <?= $ref['referral_status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="update_referral" value="1">
                                        <input type="hidden" name="referral_id" value="<?= $ref['referral_id']; ?>">
                                        <select name="referral_status" class="form-select form-select-sm">
                                            <option <?= $ref['referral_status']=='Pending'?'selected':''; ?>>Pending</option>
                                            <option <?= $ref['referral_status']=='Completed'?'selected':''; ?>>Completed</option>
                                            <option <?= $ref['referral_status']=='Cancelled'?'selected':''; ?>>Cancelled</option>
                                        </select>
                                        <input type="hidden" name="completion_info" value="Updated via portal">
                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-check-lg"></i></button>
                                    </form>
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