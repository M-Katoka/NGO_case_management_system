<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define Base Directory
define('BASE_DIR', dirname(__DIR__));

// Load DB Connection
require_once BASE_DIR . '/db.php';

/**
 * Global RBAC authorization guard
 */
function require_role(array $allowed_roles): void {
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', $allowed_roles, true)) {
        header("Location: /dashboard.php?error=unauthorized");
        exit();
    }
}