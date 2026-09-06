<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_student();

$stmt = db()->prepare("SELECT full_name, student_id FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$currentStudent = $stmt->fetch();
?>
<nav class="navbar navbar-dark portal-navbar sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="../dashboard/index.php">🎓 Student Portal</a>
        <div class="d-flex align-items-center gap-3">
            <a class="nav-link text-white" href="../dashboard/notifications.php">🔔 Notifications</a>
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                    👤 <?= e($currentStudent['full_name'] ?? 'Student') ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="../profile/index.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="../profile/edit.php">Edit Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <aside class="col-lg-2 sidebar p-0">
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action" href="../dashboard/index.php">🏠 Dashboard</a>
                <a class="list-group-item list-group-item-action" href="../profile/index.php">👤 Profile</a>
                <a class="list-group-item list-group-item-action" href="../dashboard/attendance.php">📊 Attendance</a>
                <a class="list-group-item list-group-item-action" href="../dashboard/cgpa.php">🎓 CGPA</a>
                <a class="list-group-item list-group-item-action" href="../dashboard/fees.php">💰 Fees</a>
                <a class="list-group-item list-group-item-action" href="../dashboard/certificates.php">📄 Certificates</a>
                <a class="list-group-item list-group-item-action" href="../dashboard/notifications.php">🔔 Notifications</a>
                <a class="list-group-item list-group-item-action text-danger" href="../logout.php">🚪 Logout</a>
            </div>
        </aside>
        <main class="col-lg-10 p-4">
