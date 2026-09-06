<?php
require_once __DIR__ . '/../config/database.php';
require_student();
$stmt = db()->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch();

$title = 'Profile | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">My Profile</h2>
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6"><b>Full Name</b><div><?= e($student['full_name']) ?></div></div>
            <div class="col-md-6"><b>Student ID</b><div><?= e($student['student_id']) ?></div></div>
            <div class="col-md-6"><b>Department</b><div><?= e($student['department']) ?></div></div>
            <div class="col-md-6"><b>Email</b><div><?= e($student['email']) ?></div></div>
            <div class="col-md-6"><b>Phone</b><div><?= e($student['phone']) ?></div></div>
            <div class="col-md-6"><b>CGPA</b><div><?= e((string)$student['cgpa']) ?></div></div>
        </div>
        <a href="edit.php" class="btn btn-primary mt-4">Edit Profile</a>
    </div>
</div>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
