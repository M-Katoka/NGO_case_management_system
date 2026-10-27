<?php
// demo.php

require_once __DIR__ . '/app/bootstrap.php';

echo "<h3>Seeding Sample Demo Data...</h3>";

try {
    // 1. Insert Sample Beneficiary
    $stmt = $pdo->prepare("INSERT INTO beneficiaries (first_name, last_name, date_of_birth, sex, contact_info, location, registration_date, registered_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute(['Grace', 'Chanda', '1998-04-12', 'Female', '+260977001122', 'Lusaka Central', date('Y-m-d'), 1]);
    $b_id = $pdo->lastInsertId();

    // 2. Insert Sample Case
    $stmt = $pdo->prepare("INSERT INTO cases (beneficiary_id, case_type, priority, assigned_worker_id, date_opened, case_status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$b_id, 'Legal & Livelihood Support', 'High', 1, date('Y-m-d'), 'Open']);

    echo "<p style='color:green;'>Demo data inserted successfully!</p>";
    echo "<a href='dashboard.php'>Go to Dashboard</a>";
} catch (\PDOException $e) {
    echo "<p style='color:red;'>Demo seeding failed or data already exists: " . htmlspecialchars($e->getMessage()) . "</p>";
}