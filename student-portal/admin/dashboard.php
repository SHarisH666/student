<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();

$totalStudents = db()->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totalCertificates = db()->query("SELECT COUNT(*) FROM certificates")->fetchColumn();
$totalNotifications = db()->query("SELECT COUNT(*) FROM notifications")->fetchColumn();
$totalDue = db()->query("SELECT COALESCE(SUM(due_fee),0) FROM fees")->fetchColumn();

$title = 'Admin Dashboard';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
?>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">🎓 Student Portal Admin</span>
        <a class="btn btn-outline-light" href="logout.php">Logout</a>
    </div>
</nav>
<div class="container py-5">
    <h2 class="fw-bold">Admin Dashboard</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-3"><div class="stat-card"><span>Students</span><strong><?= (int)$totalStudents ?></strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Certificates</span><strong><?= (int)$totalCertificates ?></strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Notifications</span><strong><?= (int)$totalNotifications ?></strong></div></div>
        <div class="col-md-3"><div class="stat-card"><span>Total Fee Due</span><strong>₹<?= number_format((float)$totalDue, 2) ?></strong></div></div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5>Management</h5>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-primary" href="students.php">Students</a>
                <a class="btn btn-primary" href="attendance.php">Attendance</a>
                <a class="btn btn-primary" href="fees.php">Fees</a>
                <a class="btn btn-primary" href="notifications.php">Notifications</a>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
