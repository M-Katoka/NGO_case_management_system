<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') { 
    header("Location: dashboard.php?error=unauthorized"); 
    exit(); 
}
require_once 'db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? '');

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $error = "All account provisioning fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format supplied.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $role]);
            $message = "User account created successfully!";
        } catch (\PDOException $e) {
            $error = "Error: Username or email already exists in the database.";
        }
    }
}

$users = $pdo->query("SELECT user_id, username, email, role, created_at FROM users ORDER BY user_id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>User Account Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4 fade-in">
        <a href="dashboard.php" class="btn btn-outline-secondary mb-4 shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
        
        <div class="mb-4">
            <h2><i class="bi bi-shield-lock-fill text-danger me-2"></i> User Administration & RBAC</h2>
            <p class="text-muted mb-0">Create accounts and assign institutional roles for system access control[cite: 1, 2, 3, 4].</p>
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

        <!-- Create User Card -->
        <div class="card shadow-sm mb-5">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3 text-danger"><i class="bi bi-person-plus-fill me-2"></i> Add New System User</h5>
                <form method="POST" class="row g-3 needs-validation" novalidate>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Username *</label>
                        <input type="text" name="username" class="form-control" required>
                        <div class="invalid-feedback">Username is required.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Email Address *</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback">Valid email is required.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Temporary Password *</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Assigned Role *</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role...</option>
                            <option value="CaseWorker">Case Worker</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Management">Management</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <div class="invalid-feedback">Role selection is required.</div>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-danger px-4"><i class="bi bi-person-check-fill me-1"></i> Provision Account</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Directory Table -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3"><i class="bi bi-people-fill me-2"></i> Registered System Users</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mt-2">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $u): ?>
                            <tr>
                                <td class="fw-semibold">#<?= $u['user_id']; ?></td>
                                <td><?= htmlspecialchars($u['username']); ?></td>
                                <td><?= htmlspecialchars($u['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?= $u['role']=='Admin'?'danger':($u['role']=='Supervisor'?'warning text-dark':($u['role']=='Management'?'info':'primary')); ?>">
                                        <?= htmlspecialchars($u['role']); ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?= htmlspecialchars($u['created_at']); ?></td>
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