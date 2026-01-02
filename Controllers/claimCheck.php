<?php
session_start();
require_once('../models/claimModel.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: ../views/Login/login.php');
  exit;
}

if (!isset($_POST['submit'])) {
  header('Location: ../views/Post/index.php');
  exit;
}

$post_id = intval($_POST['post_id'] ?? 0);
$name = trim($_POST['claimant_name'] ?? '');
$phone = trim($_POST['claimant_phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($post_id <= 0 || $name==='' || $phone==='') {
  header('Location: ../views/Post/details.php?id=' . $post_id . '&err=1');
  exit;
}

$userId = $_SESSION['user']['id'] ?? null;

$claim = [
  'post_id' => $post_id,
  'user_id' => $userId,
  'claimant_name' => $name,
  'claimant_phone' => $phone,
  'message' => $message
];

if (addClaim($claim)) {
  header('Location: ../views/Post/details.php?id=' . $post_id . '&msg=claim_sent');
  exit;
}

header('Location: ../views/Post/details.php?id=' . $post_id . '&err=db');
exit;
