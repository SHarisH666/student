<?php
require_once __DIR__ . '/../config/database.php';
require_student();
$stmt = db()->prepare("SELECT * FROM certificates WHERE student_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['student_id']]);
$certificates = $stmt->fetchAll();

$title = 'Certificates | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">Certificates</h2>
<div class="row g-4 mt-2">
<?php foreach ($certificates as $c): ?>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5><?= e($c['certificate_type']) ?></h5>
                <p class="text-muted"><?= e($c['status']) ?></p>
                <a class="btn btn-primary" href="../certificates/generate.php?id=<?= (int)$c['id'] ?>">Download PDF</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
