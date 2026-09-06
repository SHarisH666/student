<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();

$students = db()->query("SELECT id, student_id, full_name, department, email, phone, cgpa FROM students ORDER BY id DESC")->fetchAll();

$title = 'Students | Admin';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
?>
<div class="container py-5">
    <a href="dashboard.php">← Dashboard</a>
    <h2 class="fw-bold mt-3">Students</h2>
    <div class="table-responsive mt-3">
        <table class="table table-bordered bg-white">
            <thead><tr><th>Student ID</th><th>Name</th><th>Department</th><th>Email</th><th>Phone</th><th>CGPA</th></tr></thead>
            <tbody>
            <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= e($s['student_id']) ?></td>
                    <td><?= e($s['full_name']) ?></td>
                    <td><?= e($s['department']) ?></td>
                    <td><?= e($s['email']) ?></td>
                    <td><?= e($s['phone']) ?></td>
                    <td><?= e((string)$s['cgpa']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
