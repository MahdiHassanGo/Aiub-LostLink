<?php
session_start();

if (!isset($_COOKIE['status']) || $_COOKIE['status'] !== 'true' || !isset($_SESSION['user'])) {
  header("Location: ../Views/Login/login.php");
  exit;
}

require_once(__DIR__ . '/../Models/notificationModel.php');

$userId = currentUserId();
if ($userId <= 0) {
  header("Location: ../Views/Login/login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'mark_all_read') {
    markAllNotificationsRead($userId);
  } elseif ($action === 'clear_all') {
    clearNotifications($userId);
  } elseif ($action === 'mark_read') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) markNotificationRead($id, $userId);
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

$notifications = getNotificationsByUser($userId);

require_once(__DIR__ . '/../Views/Notification/notifications.php');
?>