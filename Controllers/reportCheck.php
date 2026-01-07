<?php
session_start();
require_once('sessionCheck.php');
require_once('../Models/postModel.php');
require_once('../Models/reportModel.php');
require_once('../Models/notificationModel.php');

if (!isset($_SESSION['user'])) {
  header('location: ../views/Login/login.php');
  exit;
}

$userId = (int)($_SESSION['user']['id'] ?? 0);

$postId = 0;
if (isset($_GET['post_id'])) $postId = (int)$_GET['post_id'];
if (isset($_POST['post_id'])) $postId = (int)$_POST['post_id'];

if ($postId <= 0) {
  header('location: ../views/Post/index.php');
  exit;
}

$post = getPostById($postId);
if (!$post) {
  header('location: ../views/Post/index.php');
  exit;
}

$err = $_GET['err'] ?? '';
$msg = $_GET['msg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $target = $_POST['target'] ?? 'post';
  $reasonsArr = $_POST['reasons'] ?? [];
  $details = trim($_POST['details'] ?? '');

  if (!is_array($reasonsArr) || count($reasonsArr) === 0) {
    header('location: /WebTechnology-Project/controllers/reportCheck.php?post_id=' . $postId . '&err=1');
    exit;
  }

  $reasons = implode(', ', $reasonsArr);

  $reportedUserId = (int)($post['user_id'] ?? 0);

  $report = [
    'post_id' => $postId,
    'reporter_id' => $userId,
    'reported_user_id' => $reportedUserId,
    'target' => $target,
    'reasons' => $reasons,
    'details' => $details,
    'status' => 'pending'
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

    header('location: /WebTechnology-Project/views/Post/details.php?id=' . $postId . '&msg=report_sent');
    exit;
  }

  header('location: /WebTechnology-Project/controllers/reportCheck.php?post_id=' . $postId . '&err=db');
  exit;
}

require_once('../Views/Report/report.php');
?>