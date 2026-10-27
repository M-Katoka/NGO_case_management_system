<?php
// tests/rules.php

require_once __DIR__ . '/../app/bootstrap.php';

echo "<h2>NGO System RBAC Rule Matrix Test</h2>";

$roles = ['CaseWorker', 'Supervisor', 'Management', 'Admin'];
$pages = [
    'beneficiaries.php' => ['CaseWorker', 'Supervisor', 'Admin'],
    'cases.php' => ['CaseWorker', 'Supervisor', 'Admin'],
    'reports.php' => ['Management', 'Supervisor', 'Admin'],
    'supervisor_workload.php' => ['Supervisor', 'Admin'],
    'users_admin.php' => ['Admin']
];

echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse;'>";
echo "<tr><th>Module Page</th>";
foreach ($roles as $r) { echo "<th>$r</th>"; }
echo "</tr>";

foreach ($pages as $page => $allowed) {
    echo "<tr><td><strong>$page</strong></td>";
    foreach ($roles as $r) {
        $access = in_array($r, $allowed, true) ? "<span style='color:green;'>ALLOWED</span>" : "<span style='color:red;'>DENIED</span>";
        echo "<td>$access</td>";
    }
    echo "</tr>";
}
echo "</table>";