<?php
require_once 'db.php';

$newPassword = 'password123';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Check if admin user exists, update or insert
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin_mwisa'");
$stmt->execute();
if ($stmt->fetchColumn() > 0) {
    $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = 'admin_mwisa'");
    $update->execute([$hashedPassword]);
    $msg = "Password successfully reset for 'admin_mwisa'! New password is: <strong>password123</strong>";
} else {
    $insert = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('admin_mwisa', 'mwisa@ngo.org', ?, 'Admin')");
    $insert->execute([$hashedPassword]);
    $msg = "Test user 'admin_mwisa' created successfully! Password is: <strong>password123</strong>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4 text-center" style="max-width: 400px;">
        <h4 class="text-success mb-3">Password Reset Complete</h4>
        <p><?= $msg; ?></p>
        <a href="login.php" class="btn btn-primary mt-2">Proceed to Login</a>
    </div>
</body>
</html>