<?php
session_start();

require_once('../Models/db.php');
require_once('../Models/commentModel.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: ../views/Login/login.php');
  exit;
}

if (!isset($_POST['submit'])) {
  header('Location: ../views/Post/index.php');
  exit;
}

$userId = (int)($_SESSION['user']['id'] ?? 0);
$username = $_SESSION['user']['username'] ?? '';

$postId = (int)($_POST['post_id'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($postId <= 0 || $comment === '' || $userId <= 0) {
  header('Location: ../views/Post/details.php?id=' . $postId . '&c_err=1');
  exit;
}

addComment($postId, $userId, $username, $comment);

header('Location: ../views/Post/details.php?id=' . $postId . '&c_msg=1');
exit;
