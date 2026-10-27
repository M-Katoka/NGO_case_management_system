<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['CaseWorker', 'Supervisor', 'Admin'])) { 
    header("Location: dashboard.php?error=unauthorized"); 
    exit(); 
}
require_once 'db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $sex = trim($_POST['sex'] ?? '');
    $contact_info = trim($_POST['contact_info'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $registration_date = trim($_POST['registration_date'] ?? date('Y-m-d'));

    // Server-side validation
    if (empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($sex) || empty($location)) {
        $error = "All required fields must be completed.";
    } elseif (strtotime($date_of_birth) > time()) {
        $error = "Date of birth cannot be in the future.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO beneficiaries (first_name, last_name, date_of_birth, sex, contact_info, location, registration_date, registered_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $date_of_birth, $sex, $contact_info, $location, $registration_date, $_SESSION['user_id']]);
            $message = "Beneficiary registered successfully!";
        } catch (\PDOException $e) {
            $error = "Database error: Unable to register beneficiary.";
        }
    }
}

$beneficiaries = $pdo->query("SELECT * FROM beneficiaries ORDER BY beneficiary_id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Beneficiary Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4 fade-in">
        <a href="dashboard.php" class="btn btn-outline-secondary mb-4 shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-people-fill text-primary me-2"></i> Beneficiary Registration & Management</h2>
                <p class="text-muted mb-0">Record and monitor individuals receiving organizational services and support.</p>
            </div>
        </div>
        
        <?php if($message): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Registration Card -->
        <div class="card shadow-sm mb-5">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3 text-primary"><i class="bi bi-person-plus me-2"></i> Register New Beneficiary</h5>
                <form method="POST" class="row g-3 needs-validation" novalidate>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">First Name *</label>
                        <input type="text" name="first_name" class="form-control" required>
                        <div class="invalid-feedback">First name is required.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" required>
                        <div class="invalid-feedback">Last name is required.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Date of Birth *</label>
                        <input type="date" name="date_of_birth" class="form-control" max="<?= date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">Valid date of birth is required.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Sex *</label>
                        <select name="sex" class="form-select" required>
                            <option value="">Choose...</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                        <div class="invalid-feedback">Please select a sex.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Contact Info</label>
                        <input type="text" name="contact_info" class="form-control" placeholder="Phone or Email">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Location / Address *</label>
                        <input type="text" name="location" class="form-control" required placeholder="Community / District">
                        <div class="invalid-feedback">Location is required.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Registration Date *</label>
                        <input type="date" name="registration_date" value="<?= date('Y-m-d'); ?>" class="form-control" required>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Save Beneficiary</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Directory Table -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3"><i class="bi bi-table me-2"></i> Registered Beneficiaries Directory</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mt-2">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>DOB</th>
                                <th>Sex</th>
                                <th>Location</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($beneficiaries as $b): ?>
                            <tr>
                                <td class="fw-semibold">#<?= $b['beneficiary_id']; ?></td>
                                <td><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']); ?></td>
                                <td><?= htmlspecialchars($b['date_of_birth']); ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($b['sex']); ?></span></td>
                                <td><?= htmlspecialchars($b['location']); ?></td>
                                <td class="text-muted"><?= htmlspecialchars($b['contact_info'] ?: 'N/A'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap client-side validation script
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>