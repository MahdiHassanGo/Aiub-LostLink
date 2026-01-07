<?php
session_start();
require_once('../Models/messageModel.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

$userId = (int)$_SESSION['user']['id'];

if ($userId <= 0) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

$otherId = 0;
if (isset($_GET['user_id'])) $otherId = (int)$_GET['user_id'];

if ($otherId > 0) {
  $otherUser = getUserById($otherId);
  $thread = getThreadMessages($userId, $otherId);
  markThreadRead($userId, $otherId);

  require_once(__DIR__ . '/../Views/Messages/thread.php');
  exit;
}

$chats = getInboxChats($userId);
require_once(__DIR__ . '/../Views/Messages/messages.php');
?>