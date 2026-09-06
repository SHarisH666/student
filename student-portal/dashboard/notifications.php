<?php
require_once __DIR__ . '/../config/database.php';
require_student();
$stmt = db()->prepare("SELECT * FROM notifications WHERE student_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['student_id']]);
$notifications = $stmt->fetchAll();

$title = 'Notifications | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">Notifications</h2>
<div class="mt-3">
<?php foreach ($notifications as $n): ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5><?= e($n['title']) ?></h5>
            <p><?= e($n['message']) ?></p>
            <small class="text-muted"><?= e($n['created_at']) ?></small>
        </div>
    </div>
<?php endforeach; ?>
<?php if (!$notifications): ?><p class="text-muted">No notifications.</p><?php endif; ?>
</div>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
