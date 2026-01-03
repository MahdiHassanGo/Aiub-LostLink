<?php
session_start();
require_once('../models/postModel.php');
require_once('/../Models/notificationModel.php');


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

$post = [
  'title' => $title,
  'location' => $location,
  'phone' => $phone,
  'student_id' => $student_id,
  'category' => $category,
  'description' => $description
];

if (addPost($post)) {
  header('Location: ../views/Post/index.php?category=' . urlencode($category) . '&msg=posted');
  exit;
}
addNotification($_SESSION['user']['id'], 'post', 'Post created', 'Your post was created.', '/WebTechnology-Project/Views/Post/index.php');


header('Location: ../views/Post/create.php?err=db');
exit;
