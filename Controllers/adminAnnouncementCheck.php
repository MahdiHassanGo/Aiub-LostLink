<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/adminCheck.php'); // admin-only guard
require_once(__DIR__ . '/../models/announcementModel.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('location: ../views/AdminAnnouncement/AdminAnnouncement.php');
  exit;
}

if (!isset($_POST['submit'])) {
  header('location: ../views/AdminAnnouncement/AdminAnnouncement.php');
  exit;
}

$message = trim($_POST['message'] ?? '');
$active  = isset($_POST['active']) ? 1 : 0;

if ($message === '') {
  header('location: ../views/AdminAnnouncement/AdminAnnouncement.php?msg=empty');
  exit;
}

$createdBy = (int)($_SESSION['user']['id'] ?? 0);

$status = setAnnouncement($message, $active, $createdBy);

if ($status) {
  header('location: ../views/AdminAnnouncement/AdminAnnouncement.php?msg=saved');
  exit;
}

header('location: ../views/AdminAnnouncement/AdminAnnouncement.php?msg=failed');
exit;
