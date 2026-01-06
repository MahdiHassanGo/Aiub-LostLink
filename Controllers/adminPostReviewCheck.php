<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/adminCheck.php');       // admin-only guard (user-mgt style)
require_once(__DIR__ . '/../models/postModel.php');

if (!isset($_POST['submit'])) {
  header('location: ../views/AdminPostReview/AdminPostReview.php');
  exit;
}

$postId = (int)($_POST['post_id'] ?? 0);
$status = $_POST['status'] ?? '';

if ($postId <= 0) {
  header('location: ../views/AdminPostReview/AdminPostReview.php?msg=invalid');
  exit;
}

if (updatePostStatus($postId, $status)) {
  header('location: ../views/AdminPostReview/AdminPostReview.php?msg=updated');
  exit;
}

header('location: ../views/AdminPostReview/AdminPostReview.php?msg=failed');
exit;
