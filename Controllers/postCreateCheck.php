<?php
session_start();
require_once('../models/postModel.php');
require_once(__DIR__ . '/../models/notificationModel.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: ../views/Login/login.php');
  exit;
}

if (!isset($_POST['submit'])) {
  header('Location: ../views/Post/create.php');
  exit;
}

$title       = trim($_POST['title'] ?? '');
$location    = trim($_POST['location'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$student_id  = trim($_POST['student_id'] ?? '');
$category    = $_POST['category'] ?? '';
$description = trim($_POST['description'] ?? '');

if ($title==='' || $location==='' || $phone==='' || $student_id==='' || $description==='' || ($category!=='Lost' && $category!=='Found')) {
  header('Location: ../views/Post/create.php?err=1');
  exit;
}

// ✅ define these from logged-in session
$userId   = (int)($_SESSION['user']['id'] ?? 0);
$username = $_SESSION['user']['username'] ?? '';
$email    = $_SESSION['user']['email'] ?? '';

$post = [
  'user_id' => $userId,
  'posted_by_username' => $username,
  'posted_by_email' => $email,

  'title' => $title,
  'location' => $location,
  'phone' => $phone,
  'student_id' => $student_id,
  'category' => $category,
  'description' => $description,

  'status' => 'pending' 
];

if (addPost($post)) {
  addNotification($userId, 'post', 'Post created', 'Your post was created and is pending approval.', '/WebTechnology-Project/Views/Post/index.php');
  header('Location: ../views/Post/index.php?category=' . urlencode($category) . '&msg=posted');
  exit;
}

header('Location: ../views/Post/create.php?err=db');
exit;
