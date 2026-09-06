<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) exit('Invalid request');
    $id = (int)$_POST['id'];
    $total = max(0, (float)$_POST['total_fee']);
    $paid = max(0, (float)$_POST['paid_fee']);
    $due = max(0, $total - $paid);
    $status = $due > 0 ? 'Pending' : 'Paid';
    db()->prepare("UPDATE fees SET total_fee=?, paid_fee=?, due_fee=?, status=? WHERE id=?")
        ->execute([$total, $paid, $due, $status, $id]);
}

$rows = db()->query("SELECT f.*, s.student_id, s.full_name FROM fees f JOIN students s ON s.id=f.student_id ORDER BY s.student_id")->fetchAll();

$title = 'Fees | Admin';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
?>
<div class="container py-5">
<a href="dashboard.php">← Dashboard</a>
<h2 class="fw-bold mt-3">Fee Management</h2>
<div class="table-responsive">
<table class="table table-bordered bg-white">
<thead><tr><th>Student</th><th>Total</th><th>Paid</th><th>Due</th><th>Update</th></tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr>
<td><?= e($r['student_id'].' - '.$r['full_name']) ?></td>
<form method="post">
<td><input class="form-control" name="total_fee" type="number" step="0.01" value="<?= e($r['total_fee']) ?>"></td>
<td><input class="form-control" name="paid_fee" type="number" step="0.01" value="<?= e($r['paid_fee']) ?>"></td>
<td>₹<?= number_format((float)$r['due_fee'],2) ?></td>
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
