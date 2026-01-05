<?php
session_start();
require_once(__DIR__ . '/../models/userModel.php');

// Must be logged in
if (!isset($_SESSION['user']['id'])) {
  header("Location: /WebTechnology-Project/views/Login/login.php?msg=login_required");
  exit;
}

$userId = (int)$_SESSION['user']['id'];
$user = getUserById($userId);

if (!$user) {
  http_response_code(404);
  die("User not found.");
}

// Load FPDF (support both paths)
$fpdf1 = __DIR__ . '/../lib/fpdf/fpdf.php';
$fpdf2 = __DIR__ . '/../lib/fpdf/fpdf186/fpdf.php';

if (file_exists($fpdf1)) {
  require_once($fpdf1);
} elseif (file_exists($fpdf2)) {
  require_once($fpdf2);
} else {
  http_response_code(500);
  die("FPDF not found. Put fpdf.php in /lib/fpdf/ or /lib/fpdf/fpdf186/");
}

$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'AIUB LostLink - My Account Details',0,1,'C');
$pdf->Ln(4);

// Info
$pdf->SetFont('Arial','',12);

$rows = [
  ['User ID', (string)($user['id'] ?? '')],
  ['Username', (string)($user['username'] ?? '')],
  ['Email', (string)($user['email'] ?? '')],
  ['Role', (string)($user['role'] ?? 'User')],
];

if (isset($user['created_at'])) {
  $rows[] = ['Created At', (string)$user['created_at']];
}

foreach ($rows as $r) {
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(45,10,$r[0],1,0);
  $pdf->SetFont('Arial','',12);
  $pdf->Cell(0,10,$r[1],1,1);
}

// Force download
$filename = "my_details_user_" . $userId . ".pdf";

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$pdf->Output('D', $filename);
exit;
