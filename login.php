<?php
session_start();
require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username/email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Login - NGO Case Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center py-5" style="min-height: 100vh;">
    <div class="container fade-in">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleDarkMode()"><i class="bi bi-moon-stars-fill"></i></button>
                    </div>
                    
                    <div class="text-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fs-2 mb-2" style="width: 60px; height: 60px;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h3 class="fw-bold">NGO Portal Login</h3>
                        <p class="text-muted small">Case Management Information System</p>
                    </div>

                    <!-- Quick Role Fill Buttons for Testing / Clarity -->
                    <div class="mb-4 p-3 bg-light rounded border">
                        <p class="small fw-semibold text-muted mb-2"><i class="bi bi-info-circle me-1"></i> Quick Fill Portal Access:</p>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="fillCredentials('admin_mwisa', 'password123')">
                                <i class="bi bi-shield-shaded me-1"></i> Login as Admin
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark" onclick="fillCredentials('supervisor_user', 'password123')">
                                <i class="bi bi-person-badge me-1"></i> Login as Supervisor
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="fillCredentials(' caseworker_user', 'password123')">
                                <i class="bi bi-person me-1"></i> Login as Case Worker
                            </button>
                        </div>
                    </div>

                    <?php if($error): ?><div class="alert alert-danger py-2 small"><?= $error; ?></div><?php endif; ?>

                    <form method="POST" id="loginForm">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Username or Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" id="usernameInput" name="username" class="form-control" placeholder="Enter username..." required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" id="passwordInput" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">Secure Login</button>
                    </form>

                    <div class="mt-3 text-center border-top pt-3">
                        <p class="text-muted small mb-1">New staff member?</p>
                        <a href="register.php" class="text-decoration-none small text-success fw-semibold">Register a new account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            html.setAttribute('data-bs-theme', html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
        }

        function fillCredentials(username, password) {
            document.getElementById('usernameInput').value = username;
            document.getElementById('passwordInput').value = password;
        }
    </script>
</body>
</html>