<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/sessionCheck.php');
require_once(__DIR__ . '/../models/claimModel.php');


$notifPath = __DIR__ . '/../models/notificationModel.php';
if (file_exists($notifPath)) require_once($notifPath);

$msgPath = __DIR__ . '/../models/messageModel.php';
if (file_exists($msgPath)) require_once($msgPath);

$postPath = __DIR__ . '/../models/postModel.php';
if (file_exists($postPath)) require_once($postPath);

$isAjax = (
  isset($_POST['ajax']) ||
  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
);

function jsonOut($ok, $msg, $extra = []) {
  header('Content-Type: application/json');
  echo json_encode(array_merge(['ok' => $ok, 'msg' => $msg], $extra));
  exit;
}

function normalizePhone($raw) {
  $p = trim((string)$raw);
  $p = preg_replace('/[^\d+]/', '', $p);
  $p = str_replace('+', '', $p);
  if (strpos($p, '88') === 0) $p = substr($p, 2);
  return $p;
}

function validBDPhone($p) {
  return preg_match('/^01[3-9]\d{8}$/', $p) === 1;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  if ($isAjax) jsonOut(false, 'invalid_request');
  header('location: ../views/Post/index.php');
  exit;
}

if (!isset($_POST['submit'])) {
  if ($isAjax) jsonOut(false, 'invalid_request');
  header('location: ../views/Post/index.php');
  exit;
}

$userId = (int)($_SESSION['user']['id'] ?? 0);
if ($userId <= 0) {
  if ($isAjax) jsonOut(false, 'login_required');
  header('location: ../views/Login/login.php');
  exit;
}

$post_id = (int)($_POST['post_id'] ?? 0);
$name    = trim($_POST['claimant_name'] ?? '');
$phone   = normalizePhone($_POST['claimant_phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($post_id <= 0) {
  if ($isAjax) jsonOut(false, 'invalid_post');
  header('location: ../views/Post/index.php');
  exit;
}

if (mb_strlen($name) < 2 || !validBDPhone($phone)) {
  if ($isAjax) jsonOut(false, 'validation_failed', ['msgHuman' => 'Name/phone invalid']);
  header('location: ../views/Post/details.php?id=' . $post_id . '&err=1');
  exit;
}

if (mb_strlen($message) > 500) {
  if ($isAjax) jsonOut(false, 'validation_failed', ['msgHuman' => 'Message too long']);
  header('location: ../views/Post/details.php?id=' . $post_id . '&err=1');
  exit;
}

$claim = [
  'post_id'        => $post_id,
  'user_id'        => $userId,
  'claimant_name'  => $name,
  'claimant_phone' => $phone,
  'message'        => $message
];

if (!addClaim($claim)) {
  if ($isAjax) jsonOut(false, 'db_failed');
  header('location: ../views/Post/details.php?id=' . $post_id . '&err=db');
  exit;
}


if ($message !== '' && function_exists('getPostById') && function_exists('createMessage')) {
  $post = getPostById($post_id);
  if ($post && isset($post['user_id'])) {
    $ownerId = (int)$post['user_id'];
    if ($ownerId > 0 && $ownerId !== $userId) {
      createMessage($userId, $ownerId, $message);
    }
  }
}

if (function_exists('addNotification')) {
  // claimant
  addNotification(
    $userId,
    'claim',
    'Claim submitted',
    'Your claim request was submitted successfully.',
    '../views/ClaimRequest/ClaimReq.php'
  );


  if (function_exists('getPostById')) {
    $post = getPostById($post_id);
    if ($post && isset($post['user_id'])) {
      $ownerId = (int)$post['user_id'];
      if ($ownerId > 0 && $ownerId !== $userId) {
        addNotification(
          $ownerId,
          'claim',
          'New claim request',
          'Someone submitted a claim request on your post.',
          '../views/Post/details.php?id=' . $post_id
        );
      }
    }
  }
}

if ($isAjax) jsonOut(true, 'claim_sent');
header('location: ../views/Post/details.php?id=' . $post_id . '&msg=claim_sent');
exit;
