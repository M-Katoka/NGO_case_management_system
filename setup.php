<?php
// setup.php

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $sql = file_get_contents(__DIR__ . '/database/schema.sql');
    $pdo->exec($sql);

    echo "<h2>NGO Case Management System Setup</h2>";
    echo "<p style='color:green;'>Database and tables successfully provisioned!</p>";
    echo "<p><a href='login.php'>Proceed to Login</a></p>";
} catch (Exception $e) {
    die("<p style='color:red;'>Setup Failed: " . htmlspecialchars($e->getMessage()) . "</p>");
}