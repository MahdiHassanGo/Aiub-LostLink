<?php
require_once(__DIR__ . '/db.php');

function currentUserId() {
  if (isset($_SESSION['user']['id'])) return (int)$_SESSION['user']['id'];
  if (isset($_SESSION['user_id'])) return (int)$_SESSION['user_id'];
  return 0;
}

function getNotificationsByUser($userId) {
  $con = getConnection();
  $userId = (int)$userId;

  $sql = "SELECT id, type, title, body, link, is_read, created_at
          FROM notifications
          WHERE user_id = $userId
          ORDER BY created_at DESC";

  $result = mysqli_query($con, $sql);

  $data = [];
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }
  }
  return $data;
}

function clearNotifications($userId) {
  $con = getConnection();
  $userId = (int)$userId;
  return mysqli_query($con, "DELETE FROM notifications WHERE user_id = $userId");
}

function markAllNotificationsRead($userId) {
  $con = getConnection();
  $userId = (int)$userId;
  return mysqli_query($con, "UPDATE notifications SET is_read = 1 WHERE user_id = $userId");
}

function markNotificationRead($notificationId, $userId) {
  $con = getConnection();
  $notificationId = (int)$notificationId;
  $userId = (int)$userId;

  $sql = "UPDATE notifications
          SET is_read = 1
          WHERE id = $notificationId AND user_id = $userId";

  return mysqli_query($con, $sql);
}
?>