<?php
require_once __DIR__ . '/../config/database.php';
require_student();
$stmt = db()->prepare("SELECT * FROM fees WHERE student_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$_SESSION['student_id']]);
$fee = $stmt->fetch();

$title = 'Fees | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">Fee Details</h2>
<?php if (!$fee): ?>
    <div class="alert alert-info mt-3">No fee record available.</div>
<?php else: ?>
<div class="row g-4 mt-2">
    <div class="col-md-4"><div class="stat-card"><span>Total Fee</span><strong>₹<?= number_format($fee['total_fee'], 2) ?></strong></div></div>
    <div class="col-md-4"><div class="stat-card"><span>Paid</span><strong>₹<?= number_format($fee['paid_fee'], 2) ?></strong></div></div>
    <div class="col-md-4"><div class="stat-card"><span>Due</span><strong>₹<?= number_format($fee['due_fee'], 2) ?></strong></div></div>
</div>
<div class="mt-4"><span class="badge <?= $fee['due_fee'] > 0 ? 'text-bg-warning' : 'text-bg-success' ?>"><?= e($fee['status']) ?></span></div>
<?php endif; ?>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
