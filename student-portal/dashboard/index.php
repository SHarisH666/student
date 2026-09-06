<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_student();

$studentId = (int)$_SESSION['student_id'];

$stmt = db()->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$studentId]);
$student = $stmt->fetch();

$att = db()->prepare("SELECT COALESCE(SUM(attended_classes),0) attended, COALESCE(SUM(total_classes),0) total FROM attendance WHERE student_id = ?");
$att->execute([$studentId]);
$attendance = $att->fetch();
$attendancePct = ($attendance['total'] ?? 0) > 0 ? round(($attendance['attended'] / $attendance['total']) * 100, 2) : 0;

$fee = db()->prepare("SELECT total_fee, paid_fee, due_fee, status FROM fees WHERE student_id = ? ORDER BY id DESC LIMIT 1");
$fee->execute([$studentId]);
$fee = $fee->fetch() ?: ['total_fee'=>0,'paid_fee'=>0,'due_fee'=>0,'status'=>'No record'];

$notes = db()->prepare("SELECT * FROM notifications WHERE student_id = ? ORDER BY created_at DESC LIMIT 5");
$notes->execute([$studentId]);
$notifications = $notes->fetchAll();

$title = 'Dashboard | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">Welcome, <?= e($student['full_name']) ?> 👋</h2>
<p class="text-muted"><?= e($student['department']) ?> · Student ID: <?= e($student['student_id']) ?></p>

<div class="row g-4 mt-2">
    <div class="col-md-4"><div class="stat-card"><span>Attendance</span><strong><?= e((string)$attendancePct) ?>%</strong></div></div>
    <div class="col-md-4"><div class="stat-card"><span>CGPA</span><strong><?= e((string)$student['cgpa']) ?></strong></div></div>
    <div class="col-md-4"><div class="stat-card"><span>Fee Due</span><strong>₹<?= number_format((float)$fee['due_fee'], 2) ?></strong></div></div>
</div>

<div class="row g-4 mt-2">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5>Student Information</h5>
                <div class="row">
                    <div class="col-md-6"><b>Full Name</b><br><?= e($student['full_name']) ?></div>
                    <div class="col-md-6"><b>Department</b><br><?= e($student['department']) ?></div>
                    <div class="col-md-6 mt-3"><b>Email</b><br><?= e($student['email']) ?></div>
                    <div class="col-md-6 mt-3"><b>Phone</b><br><?= e($student['phone']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5>Recent Notifications</h5>
                <?php if (!$notifications): ?>
                    <p class="text-muted">No notifications.</p>
                <?php else: foreach ($notifications as $n): ?>
                    <div class="notification-item">
                        <strong><?= e($n['title']) ?></strong>
                        <div><?= e($n['message']) ?></div>
                        <small class="text-muted"><?= e($n['created_at']) ?></small>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
