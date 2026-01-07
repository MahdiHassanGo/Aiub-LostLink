<?php
session_start();

require_once('../Models/postModel.php');
require_once('../Models/reportModel.php');
require_once('../Models/notificationModel.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: ../views/Login/login.php');
  exit;
}

$userId = (int)($_SESSION['user']['id'] ?? 0);
$username = $_SESSION['user']['username'] ?? '';

if ($userId <= 0) {
  header('Location: ../views/Login/login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $postId = (int)($_POST['post_id'] ?? 0);
  $target = trim($_POST['target'] ?? 'post');
  $details = trim($_POST['details'] ?? '');

  $reasonsArr = [];
  if (isset($_POST['reasons']) && is_array($_POST['reasons'])) {
    $reasonsArr = $_POST['reasons'];
  }

  if ($postId <= 0 || count($reasonsArr) === 0) {
    header('Location: ../views/Report/report.php?post_id=' . $postId . '&err=1');
    exit;
  }

  $post = getPostById($postId);
  if (!$post || !isset($post['user_id'])) {
    header('Location: ../views/Post/index.php');
    exit;
  }

  $ownerId = (int)$post['user_id'];

  if ($ownerId <= 0) {
    header('Location: ../views/Post/index.php');
    exit;
  }

  $reasonsStr = '';
  for ($i = 0; $i < count($reasonsArr); $i++) {
    $r = trim($reasonsArr[$i]);
    if ($r !== '') {
      if ($reasonsStr !== '') $reasonsStr .= ', ';
      $reasonsStr .= $r;
    }
  }

  if ($reasonsStr === '') {
    header('Location: ../views/Report/report.php?post_id=' . $postId . '&err=1');
    exit;
  }

  $report = [
    'post_id' => $postId,
    'reporter_id' => $userId,
    'reported_user_id' => $ownerId,
    'target' => ($target === 'user') ? 'user' : 'post',
    'reasons' => $reasonsStr,
    'details' => $details
  ];

  if (addReport($report)) {

  $admins = getAdminUserIds();
  for ($i = 0; $i < count($admins); $i++) {
    addNotification(
      $admins[$i],
      'report',
      'New report received',
      'Someone submitted a report. Status is pending.',
      '/WebTechnology-Project/controllers/adminReportsCheck.php'
    );
  }

  addNotification(
    $userId,
    'report',
    'Report submitted',
    'Your report is now pending review.',
    '/WebTechnology-Project/controllers/myReportsCheck.php'
  );

  header('Location: ../views/Post/details.php?id=' . $postId);
  exit;
}


  header('Location: ../views/Report/report.php?post_id=' . $postId . '&err=db');
  exit;
}

$postId = (int)($_GET['post_id'] ?? 0);
if ($postId <= 0) {
  header('Location: ../views/Post/index.php');
  exit;
}

$post = getPostById($postId);
if (!$post) {
  header('Location: ../views/Post/index.php');
  exit;
}

$err = $_GET['err'] ?? '';
require_once('../Views/Report/report.php');
