<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_student();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("
    SELECT c.*, s.full_name, s.student_id, s.department
    FROM certificates c
    JOIN students s ON s.id = c.student_id
    WHERE c.id = ? AND c.student_id = ?
");
$stmt->execute([$id, $_SESSION['student_id']]);
$c = $stmt->fetch();

if (!$c) {
    http_response_code(404);
    exit('Certificate not found.');
}

// Minimal dependency-free PDF generator.
// For production, replace this with Dompdf/TCPDF for professional certificates.
$lines = [
    'STUDENT PORTAL',
    '',
    strtoupper($c['certificate_type']),
    '',
    'This is to certify that',
    '',
    $c['full_name'],
    '',
    'Student ID: ' . $c['student_id'],
    'Department: ' . $c['department'],
    '',
    'Certificate No: ' . $c['certificate_number'],
    'Date: ' . date('d-m-Y'),
];

function pdf_escape($s) {
    return str_replace(['\\','(',')'], ['\\\\','\\(','\\)'], $s);
}

$content = "BT\n/F1 16 Tf\n";
$y = 760;
foreach ($lines as $line) {
    $content .= "50 {$y} Td (" . pdf_escape($line) . ") Tj\n";
    $y -= 28;
    $content .= "0 0 Td\n";
}
$content .= "ET\n";

$objects = [];
$objects[] = "<< /Type /Catalog /Pages 2 0 R >>";
$objects[] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
$objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>";
$objects[] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream";
$objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";

$pdf = "%PDF-1.4\n";
$offsets = [0];
foreach ($objects as $i => $obj) {
    $offsets[$i + 1] = strlen($pdf);
    $pdf .= ($i + 1) . " 0 obj\n" . $obj . "\nendobj\n";
}
$xref = strlen($pdf);
$pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
for ($i = 1; $i <= count($objects); $i++) {
    $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
}
$pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . preg_replace('/[^A-Za-z0-9_-]/', '_', $c['certificate_type']) . '.pdf"');
echo $pdf;
