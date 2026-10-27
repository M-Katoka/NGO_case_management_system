<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';

$searchQuery = $_GET['query'] ?? '';
$results = [];

if (!empty($searchQuery)) {
    $stmt = $pdo->prepare("
        SELECT b.*, c.case_id, c.case_type, c.case_status, c.priority 
        FROM beneficiaries b 
        LEFT JOIN cases c ON b.beneficiary_id = c.beneficiary_id 
        WHERE b.first_name LIKE ? OR b.last_name LIKE ? OR b.location LIKE ? OR b.contact_info LIKE ?
    ");
    $searchTerm = "%$searchQuery%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Search & Retrieval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4 fade-in">
        <a href="dashboard.php" class="btn btn-outline-secondary mb-4 shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
        
        <div class="mb-4">
            <h2><i class="bi bi-search text-primary me-2"></i> Advanced Search & Retrieval</h2>
            <p class="text-muted mb-0">Locate beneficiary profiles and associated cases quickly by name, location, or contact info.</p>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <div class="col-md-10">
                        <input type="text" name="query" class="form-control" placeholder="Search by name, location, or contact..." value="<?= htmlspecialchars($searchQuery); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 shadow-sm"><i class="bi bi-search me-1"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if(!empty($searchQuery)): ?>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-3">Search Results for "<?= htmlspecialchars($searchQuery); ?>"</h5>
                <?php if(count($results) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mt-2">
                        <thead>
                            <tr>
                                <th>Beneficiary ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Case Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($results as $r): ?>
                            <tr>
                                <td>#<?= $r['beneficiary_id']; ?></td>
                                <td><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></td>
                                <td><?= htmlspecialchars($r['location']); ?></td>
                                <td><?= htmlspecialchars($r['contact_info']); ?></td>
                                <td>
                                    <?php if($r['case_id']): ?>
                                        <span class="badge bg-success"><?= $r['case_status']; ?> (<?= $r['case_type']; ?>)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Active Case</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($r['case_id']): ?>
                                        <a href="case_details.php?id=<?= $r['case_id']; ?>" class="btn btn-sm btn-outline-primary">View Case</a>
                                    <?php else: ?>
                                        <a href="cases.php" class="btn btn-sm btn-outline-success">Open Case</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <p class="text-muted mb-0">No matching records found.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>