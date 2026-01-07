<?php
session_start();
require_once(__DIR__ . '/../Models/notificationModel.php');

if (!isset($_COOKIE['status']) || $_COOKIE['status'] !== 'true' || !isset($_SESSION['user'])) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

$userId = getUserIdFromSession();

if ($userId <= 0) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = '';
  if (isset($_POST['action'])) $action = $_POST['action'];

  if ($action === 'mark_all_read') {
    markAllNotificationsRead($userId);
  } else if ($action === 'clear_all') {
    clearNotifications($userId);
  } else if ($action === 'mark_read') {
    $id = 0;
    if (isset($_POST['id'])) $id = (int)$_POST['id'];
    if ($id > 0) markNotificationRead($id, $userId);
  }

  header('Location: /WebTechnology-Project/Controllers/notificationsCheck.php');
  exit;
}

$notifications = getNotificationsByUser($userId);
$unreadCount = getUnreadNotificationCount($userId);

require_once(__DIR__ . '/../Views/Notification/notifications.php');
?>