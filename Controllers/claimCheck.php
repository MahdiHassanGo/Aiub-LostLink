<?php
session_start();
require_once('../models/claimModel.php');
require_once('../Models/notificationModel.php');
require_once('../Models/postModel.php');


if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
    header('Location: ../views/Login/login.php');
    exit;
}

if (!isset($_POST['submit'])) {
    header('Location: ../views/Post/index.php');
    exit;
}

$post_id = intval($_POST['post_id'] ?? 0);
$name    = trim($_POST['claimant_name'] ?? '');
$phone   = trim($_POST['claimant_phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($post_id <= 0 || $name === '' || $phone === '') {
    header('Location: ../views/Post/details.php?id=' . $post_id . '&err=1');
    exit;
}

$claim = [
    'post_id'        => $post_id,
    'user_id'        => $_SESSION['user']['id'],
    'claimant_name'  => $name,
    'claimant_phone' => $phone,
    'message'        => $message
];

if (addClaim($claim)) {

  addNotification($userId, 'claim', 'Message sent', 'Your verification message was sent.', '/WebTechnology-Project/Controllers/messagesCheck.php');

  $post = getPostById($post_id);
  if ($post && isset($post['user_id'])) {
    $ownerId = (int)$post['user_id'];
    if ($ownerId > 0 && $ownerId != $userId) {
      addNotification($ownerId, 'claim', 'New claim message', 'Someone sent a verification message on your post.', '/WebTechnology-Project/Controllers/messagesCheck.php?post_id=' . $post_id);
    }
  }

  header('Location: /WebTechnology-Project/Views/Post/details.php?id=' . $post_id . '&msg=claim_sent');
  exit;
}

header('Location: /WebTechnology-Project/Views/Post/details.php?id=' . $post_id . '&err=db');
exit;
    header('Location: ../views/Post/details.php?id=' . $post_id . '&msg=claim_sent');
    exit;
}

header('Location: ../views/Post/details.php?id=' . $post_id . '&err=db');
exit;
