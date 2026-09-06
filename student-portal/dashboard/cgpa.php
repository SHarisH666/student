<?php
require_once __DIR__ . '/../config/database.php';
require_student();
$stmt = db()->prepare("SELECT full_name, student_id, cgpa FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch();

$title = 'CGPA | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">CGPA</h2>
<div class="cgpa-card mt-4">
    <div class="small">Current CGPA</div>
    <div class="display-1 fw-bold"><?= e((string)$student['cgpa']) ?></div>
    <div><?= e($student['full_name']) ?> · <?= e($student['student_id']) ?></div>
</div>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
