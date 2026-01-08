


<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$isAjax = (
  isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
);

function jsonOut($ok, $msg, $extra = []) {
  header('Content-Type: application/json');
  echo json_encode(array_merge(['ok' => $ok, 'msg' => $msg], $extra));
  exit;
}

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  if ($isAjax) jsonOut(false, 'login_required');
  header('location: ../views/Login/login.php');
  exit;
}
