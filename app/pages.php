<?php

require_once __DIR__ . '/bootstrap.php';

/**
 * Returns accessible menu items based on the user's role.
 */
function get_navigation_menu(string $role): array {
    $menu = [
        ['label' => 'Dashboard', 'url' => 'dashboard.php', 'icon' => 'bi-grid'],
    ];

    if (in_array($role, ['CaseWorker', 'Supervisor', 'Admin'], true)) {
        $menu[] = ['label' => 'Beneficiaries', 'url' => 'beneficiaries.php', 'icon' => 'bi-people'];
        $menu[] = ['label' => 'Cases', 'url' => 'cases.php', 'icon' => 'bi-journal-medical'];
        $menu[] = ['label' => 'Referrals', 'url' => 'referrals.php', 'icon' => 'bi-share'];
    }

    if (in_array($role, ['Management', 'Supervisor', 'Admin'], true)) {
        $menu[] = ['label' => 'Reports', 'url' => 'reports.php', 'icon' => 'bi-bar-chart-line'];
    }

    if (in_array($role, ['Supervisor', 'Admin'], true)) {
        $menu[] = ['label' => 'Workload', 'url' => 'supervisor_workload.php', 'icon' => 'bi-sliders'];
    }

    if ($role === 'Admin') {
        $menu[] = ['label' => 'User Admin', 'url' => 'users_admin.php', 'icon' => 'bi-shield-shaded'];
    }

    return $menu;
}