<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/adminCheck.php');
require_once(__DIR__ . '/../models/postModel.php');

$isAjax = (
  isset($_POST['ajax']) ||
  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
);

function jsonOut($ok, $msg, $extra = []) {
  header('Content-Type: application/json');
  echo json_encode(array_merge(['ok' => $ok, 'msg' => $msg], $extra));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  if ($isAjax) jsonOut(false, 'invalid');
  header('location: ../views/AdminPostReview/AdminPostReview.php');
  exit;
}

$postId = (int)($_POST['post_id'] ?? 0);
$status = $_POST['status'] ?? '';

if ($postId <= 0) {
  if ($isAjax) jsonOut(false, 'invalid');
  header('location: ../views/AdminPostReview/AdminPostReview.php?msg=invalid');
  exit;
}

$ok = updatePendingPostStatus($postId, $status);

if ($ok) {
  $newStatus = strtolower(trim((string)$status));
  if ($isAjax) jsonOut(true, 'updated', ['newStatus' => $newStatus]);
  header('location: ../views/AdminPostReview/AdminPostReview.php?msg=updated');
  exit;
}

if ($isAjax) jsonOut(false, 'failed');
header('location: ../views/AdminPostReview/AdminPostReview.php?msg=failed');
exit;
