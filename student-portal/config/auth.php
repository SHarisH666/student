<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function require_student(): void {
    if (empty($_SESSION['student_id'])) {
        header('Location: ../login.php');
        exit;
    }
}

function require_admin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verify_csrf(?string $token): bool {
    return !empty($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
