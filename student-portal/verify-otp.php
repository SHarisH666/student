<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

if (empty($_SESSION['pending_student_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$studentId = (int)$_SESSION['pending_student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $otp = trim($_POST['otp'] ?? '');
        $stmt = db()->prepare("SELECT * FROM otp_verifications WHERE student_id = ? AND verified = 0 ORDER BY id DESC LIMIT 1");
        $stmt->execute([$studentId]);
        $row = $stmt->fetch();

        if (!$row) {
            $error = 'No active OTP. Please login again.';
        } elseif (strtotime($row['expires_at']) < time()) {
            $error = 'OTP has expired. Please login again.';
        } elseif ((int)$row['attempts'] >= OTP_MAX_ATTEMPTS) {
            $error = 'Too many attempts. Please login again.';
        } else {
            db()->prepare("UPDATE otp_verifications SET attempts = attempts + 1 WHERE id = ?")->execute([$row['id']]);

            if (!password_verify($otp, $row['otp_hash'])) {
                $error = 'Incorrect OTP.';
            } else {
                db()->prepare("UPDATE otp_verifications SET verified = 1 WHERE id = ?")->execute([$row['id']]);

                session_regenerate_id(true);
                $_SESSION['student_id'] = $studentId;
                unset($_SESSION['pending_student_id'], $_SESSION['otp_created_at'], $_SESSION['dev_otp']);

                header('Location: dashboard/index.php');
                exit;
            }
        }
    }
}
?>
<?php $title = 'Verify OTP | Student Portal'; $cssPath = 'assets/css/style.css'; $jsPath = 'assets/js/app.js'; require __DIR__ . '/partials/header.php'; ?>
<div class="auth-page">
    <div class="auth-card text-center">
        <div class="logo-circle">🔐</div>
        <h2 class="fw-bold">Verify OTP</h2>
        <p class="text-muted">Enter the 6-digit verification code.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger text-start"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if (DEV_SHOW_OTP && !empty($_SESSION['dev_otp'])): ?>
            <div class="alert alert-warning">
                Development OTP: <strong><?= e($_SESSION['dev_otp']) ?></strong>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="text" name="otp" maxlength="6" inputmode="numeric"
                   class="form-control form-control-lg text-center otp-input"
                   placeholder="123456" required>
            <button class="btn btn-primary btn-lg w-100 mt-3">Verify OTP</button>
        </form>

        <a href="login.php" class="d-block mt-3">Back to Login</a>
    </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
