<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = db()->prepare("SELECT * FROM admin_users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, $admin['password'])) {
            $error = 'Invalid admin credentials.';
        } else {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<?php $title = 'Admin Login'; $cssPath = '../assets/css/style.css'; $jsPath = '../assets/js/app.js'; require __DIR__ . '/../partials/header.php'; ?>
<div class="auth-page">
    <div class="auth-card">
        <h2 class="fw-bold text-center">Admin Login</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input class="form-control mb-3" name="email" type="email" placeholder="Admin email" required>
            <input class="form-control mb-3" name="password" type="password" placeholder="Password" required>
            <button class="btn btn-dark w-100">Login</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
