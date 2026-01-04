<?php
session_start();
require_once(__DIR__ . '/../Models/messageModel.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

$userId = getUserIdFromSession();
if ($userId <= 0) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

if (!isset($_POST['send'])) {
  header('Location: /WebTechnology-Project/Controllers/messagesCheck.php');
  exit;
}

$receiverId = 0;
if (isset($_POST['receiver_id'])) $receiverId = (int)$_POST['receiver_id'];

$body = '';
if (isset($_POST['body'])) $body = trim($_POST['body']);

if ($receiverId <= 0 || $body === '') {
  header('Location: /WebTechnology-Project/Controllers/messagesCheck.php?user_id=' . $receiverId . '&err=1');
  exit;
}

createMessage($userId, $receiverId, $body);

header('Location: /WebTechnology-Project/Controllers/messagesCheck.php?user_id=' . $receiverId);
exit;
?>