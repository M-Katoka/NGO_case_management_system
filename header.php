<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="dashboard.php">
            <div class="bg-primary text-white rounded p-1 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <span>NGO Portal</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Global Search -->
            <form action="search.php" method="GET" class="d-flex mx-auto my-2 my-lg-0 position-relative" style="max-width: 360px; width: 100%;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white-50"></i>
                <input type="text" name="query" class="form-control form-control-sm top-search-input" placeholder="Search beneficiaries, cases..." required>
            </form>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item">
                    <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-grid me-1"></i> Dashboard</a>
                </li>

                <!-- Operations Dropdown -->
                <?php if(in_array($_SESSION['role'], ['CaseWorker', 'Supervisor', 'Admin'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-folder2-open me-1"></i> Operations
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item py-2" href="beneficiaries.php"><i class="bi bi-people me-2 text-primary"></i> Beneficiaries Directory</a></li>
                        <li><a class="dropdown-item py-2" href="cases.php"><i class="bi bi-journal-medical me-2 text-success"></i> Active Cases Registry</a></li>
                        <li><a class="dropdown-item py-2" href="referrals.php"><i class="bi bi-share me-2 text-warning"></i> Referral Tracking</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Management Dropdown -->
                <?php if(in_array($_SESSION['role'], ['Management', 'Supervisor', 'Admin'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-graph-up me-1"></i> Management
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item py-2" href="reports.php"><i class="bi bi-bar-chart-line me-2 text-info"></i> Analytics & Reports</a></li>
                        <?php if(in_array($_SESSION['role'], ['Supervisor', 'Admin'])): ?>
                        <li><a class="dropdown-item py-2" href="supervisor_workload.php"><i class="bi bi-sliders me-2 text-secondary"></i> Caseworker Workloads</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if($_SESSION['role'] === 'Admin'): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="users_admin.php"><i class="bi bi-shield-shaded me-1"></i> User Admin</a>
                </li>
                <?php endif; ?>

                <!-- Persistent Dark Mode Toggle Button -->
                <li class="nav-item ms-lg-2">
                    <button class="btn btn-sm btn-outline-light rounded-circle p-2" onclick="toggleDarkMode()" title="Toggle Dark/Light Mode" style="width: 36px; height: 36px;">
                        <i id="themeIcon" class="bi bi-moon-stars-fill"></i>
                    </button>
                </li>

                <!-- User Menu Dropdown -->
                <li class="nav-item dropdown ms-lg-2">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.85rem;">
                            <?= strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                        <span class="small"><?= htmlspecialchars($_SESSION['username']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li class="dropdown-header text-uppercase small text-muted">Role: <strong><?= $_SESSION['role']; ?></strong></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger py-2" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Persistent Global Theme Script -->
<script>
    // Apply theme immediately on page load from localStorage
    (function() {
        const savedTheme = localStorage.getItem('ngo_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);
    })();

    function toggleDarkMode() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('ngo_theme', newTheme);
        updateThemeIcon(newTheme);
    }

    function updateThemeIcon(theme) {
        const icon = document.getElementById('themeIcon');
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun-fill text-warning' : 'bi bi-moon-stars-fill';
        }
    }
</script>