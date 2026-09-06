<?php
require_once __DIR__ . '/../config/database.php';
require_student();
$studentId = (int)$_SESSION['student_id'];

$stmt = db()->prepare("SELECT subject, attended_classes, total_classes, percentage FROM attendance WHERE student_id = ? ORDER BY subject");
$stmt->execute([$studentId]);
$rows = $stmt->fetchAll();

$title = 'Attendance | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">Attendance</h2>
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Subject</th><th>Attended</th><th>Total</th><th>Percentage</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <?php $pct = $r['percentage'] !== null ? (float)$r['percentage'] : attendance_percentage((int)$r['attended_classes'], (int)$r['total_classes']); ?>
                    <tr>
                        <td><?= e($r['subject']) ?></td>
                        <td><?= e((string)$r['attended_classes']) ?></td>
                        <td><?= e((string)$r['total_classes']) ?></td>
                        <td><span class="badge <?= $pct < 75 ? 'text-bg-danger' : 'text-bg-success' ?>"><?= e((string)$pct) ?>%</span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
