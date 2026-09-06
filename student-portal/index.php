<?php
session_start();
if (isset($_SESSION['student_id'])) {
    header("Location: dashboard/index.php");
    exit;
}
header("Location: login.php");
exit;
