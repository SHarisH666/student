<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) exit('Invalid request');
    $titleN = trim($_POST['title'] ?? '');
    $body = trim($_POST['message'] ?? '');
    $target = $_POST['target'] ?? 'all';

    if ($titleN && $body) {
        if ($target === 'all') {
            $students = db()->query("SELECT id FROM students")->fetchAll();
        } else {
            $stmt = db()->prepare("SELECT id FROM students WHERE department = ?");
            $stmt->execute([$target]);
            $students = $stmt->fetchAll();
        }

        $insert = db()->prepare("INSERT INTO notifications (student_id, title, message) VALUES (?, ?, ?)");
        foreach ($students as $s) $insert->execute([$s['id'], $titleN, $body]);
        $message = 'Notification sent successfully.';
    }
}
$departments = db()->query("SELECT DISTINCT department FROM students WHERE department IS NOT NULL AND department <> '' ORDER BY department")->fetchAll(PDO::FETCH_COLUMN);

$title = 'Notifications | Admin';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
?>
<div class="container py-5">
<a href="dashboard.php">← Dashboard</a>
<h2 class="fw-bold mt-3">Send Notification</h2>
<?php if ($message): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?>
<form method="post" class="card border-0 shadow-sm">
<div class="card-body">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<label class="form-label">Title</label>
<input class="form-control mb-3" name="title" required>
<label class="form-label">Message</label>
<textarea class="form-control mb-3" name="message" rows="4" required></textarea>
<label class="form-label">Target</label>
<select class="form-select mb-3" name="target">
<option value="all">All Students</option>
<?php foreach($departments as $d): ?><option value="<?= e($d) ?>"><?= e($d) ?></option><?php endforeach; ?>
</select>
<button class="btn btn-primary">Send Notification</button>
</div>
</form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
