<?php
// app/actions.php

require_once __DIR__ . '/bootstrap.php';

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'toggle_theme':
        $_SESSION['theme'] = ($_SESSION['theme'] ?? 'light') === 'dark' ? 'light' : 'dark';
        echo json_encode(['status' => 'success', 'theme' => $_SESSION['theme']]);
        exit();

    case 'logout':
        session_destroy();
        header("Location: ../login.php");
        exit();

    default:
        header("Location: ../dashboard.php");
        exit();
} 