<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_student();

$stmt = db()->prepare("SELECT full_name, phone FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $name = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($name === '') {
            $error = 'Name is required.';
        } else {
            $stmt = db()->prepare("UPDATE students SET full_name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $_SESSION['student_id']]);
            header('Location: index.php');
            exit;
        }
    }
}

$title = 'Edit Profile | Student Portal';
$cssPath = '../assets/css/style.css';
$jsPath = '../assets/js/app.js';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/student_nav.php';
?>
<h2 class="fw-bold">Edit Profile</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card border-0 shadow-sm mt-3">
    <div class="card-body">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <label class="form-label">Full Name</label>
        <input class="form-control mb-3" name="full_name" value="<?= e($student['full_name']) ?>" required>
        <label class="form-label">Phone</label>
        <input class="form-control mb-3" name="phone" value="<?= e($student['phone']) ?>">
        <button class="btn btn-primary">Save Changes</button>
    </div>
</form>
<?php require __DIR__ . '/../partials/student_end.php'; ?>
