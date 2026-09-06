<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

if (!empty($_SESSION['student_id'])) {
    header('Location: dashboard/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $error = 'Invalid request. Please refresh and try again.';
    } else {
        $identity = trim($_POST['identity'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($identity === '' || $password === '') {
            $error = 'Please enter your email/phone and password.';
        } else {
            $stmt = db()->prepare("SELECT * FROM students WHERE email = ? OR phone = ? LIMIT 1");
            $stmt->execute([$identity, $identity]);
            $student = $stmt->fetch();

            if (!$student || !password_verify($password, $student['password'])) {
                $error = 'Invalid login credentials.';
            } else {
                $_SESSION['pending_student_id'] = $student['id'];
                $_SESSION['otp_created_at'] = time();

                $otp = (string)random_int(100000, 999999);
                $otpHash = password_hash($otp, PASSWORD_DEFAULT);
                $expires = date('Y-m-d H:i:s', time() + OTP_EXPIRY_MINUTES * 60);

                db()->prepare("DELETE FROM otp_verifications WHERE student_id = ?")->execute([$student['id']]);
                db()->prepare("INSERT INTO otp_verifications (student_id, otp_hash, expires_at) VALUES (?, ?, ?)")
                    ->execute([$student['id'], $otpHash, $expires]);

                // For development only. Production should send this through an email/SMS provider.
                $_SESSION['dev_otp'] = DEV_SHOW_OTP ? $otp : null;

                header('Location: verify-otp.php');
                exit;
            }
        }
    }
}
?>
<?php $title = 'Login | Student Portal'; $cssPath = 'assets/css/style.css'; $jsPath = 'assets/js/app.js'; require __DIR__ . '/partials/header.php'; ?>
<div class="auth-page">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="logo-circle">🎓</div>
            <h2 class="fw-bold">Student Portal</h2>
            <p class="text-muted">Sign in to access your academic information</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
                <label class="form-label">Email or Phone Number</label>
                <input type="text" name="identity" class="form-control form-control-lg" placeholder="student@example.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control form-control-lg" required>
            </div>
            <button class="btn btn-primary btn-lg w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Two-step verification is enabled.</small>
        </div>
    </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
