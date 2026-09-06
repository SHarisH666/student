<?php
function redirect(string $url): never {
    header("Location: $url");
    exit;
}

function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function attendance_percentage(int $attended, int $total): float {
    return $total > 0 ? round(($attended / $total) * 100, 2) : 0;
}
