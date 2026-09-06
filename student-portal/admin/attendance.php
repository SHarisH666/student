<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) exit('Invalid request');
    $id = (int)$_POST['id'];
    $attended = max(0, (int)$_POST['attended']);
    $total = max(0, (int)$_POST['total']);
    $percentage = $total ? round(($attended / $total) * 100, 2) : 0;
    db()->prepare("UPDATE attendance SET attended_classes=?, total_classes=?, percentage=? WHERE id=?")
        ->execute([$attended, $total, $percentage, $id]);
}

$rows = db()->query("SELECT a.*, s.full_name, s.student_id FROM attendance a JOIN students s ON s.id=a.student_id ORDER BY s.student_id, a.subject")->fetchAll();

$title = 'Attendance | Admin';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
?>
<div class="container py-5">
<a href="dashboard.php">← Dashboard</a>
<h2 class="fw-bold mt-3">Attendance Management</h2>
<div class="table-responsive">
<table class="table table-bordered bg-white">
<thead><tr><th>Student</th><th>Subject</th><th>Attended</th><th>Total</th><th>Update</th></tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr>
<td><?= e($r['student_id'].' - '.$r['full_name']) ?></td>
<td><?= e($r['subject']) ?></td>
<form method="post">
<td><input class="form-control" name="attended" type="number" value="<?= (int)$r['attended_classes'] ?>" min="0"></td>
<td><input class="form-control" name="total" type="number" value="<?= (int)$r['total_classes'] ?>" min="0"></td>
<td>
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
<button class="btn btn-sm btn-primary">Save</button>
</td>
</form>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
