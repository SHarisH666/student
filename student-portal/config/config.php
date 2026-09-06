<?php
// Copy this file to config/config.local.php for production secrets if needed.
// For local XAMPP, the defaults below work with a standard MySQL installation.

define('APP_NAME', 'Student Portal');
define('BASE_URL', '/student-portal');

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'student_portal');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

define('OTP_EXPIRY_MINUTES', 5);
define('OTP_MAX_ATTEMPTS', 5);
define('OTP_RESEND_SECONDS', 60);

// Development OTP delivery. Set to false and integrate a mail/SMS provider for production.
define('DEV_SHOW_OTP', true);
