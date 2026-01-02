<?php
require_once(__DIR__ . "/db.php");

function getUserIdFromSession() {
  if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
    return (int)$_SESSION['user']['id'];
  }
  return 0;
}

function addNotification($userId, $type, $title, $body, $link) {
  $con = getConnection();
  $userId = (int)$userId;

  $type = mysqli_real_escape_string($con, $type);
  $title = mysqli_real_escape_string($con, $title);
  $body = mysqli_real_escape_string($con, $body);
  $link = mysqli_real_escape_string($con, $link);

  $sql = "INSERT INTO notifications (user_id, type, title, body, link)
          VALUES ($userId, '$type', '$title', '$body', '$link')";
  return mysqli_query($con, $sql);
}

function getNotificationsByUser($userId) {
  $con = getConnection();
  $userId = (int)$userId;

  $sql = "SELECT id, user_id, type, title, body, link, is_read, created_at
          FROM notifications
          WHERE user_id = $userId
          ORDER BY created_at DESC";

  $result = mysqli_query($con, $sql);
  $list = [];

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $list[] = $row;
    }
  }

  return $list;
}

function getUnreadNotificationCount($userId) {
  $con = getConnection();
  $userId = (int)$userId;

  $sql = "SELECT COUNT(*) AS total
          FROM notifications
          WHERE user_id = $userId AND is_read = 0";

  $result = mysqli_query($con, $sql);
  $row = mysqli_fetch_assoc($result);

  if ($row && isset($row['total'])) return (int)$row['total'];
  return 0;
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

function markNotificationRead($id, $userId) {
  $con = getConnection();
  $id = (int)$id;
  $userId = (int)$userId;
  return mysqli_query($con, "UPDATE notifications SET is_read = 1 WHERE id = $id AND user_id = $userId");
}
