<?php
session_start();
require_once('../models/postModel.php');

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

if (!isset($_POST['submit'])) {
  header('Location: /WebTechnology-Project/Views/Post/create.php');
  exit;
}

$title       = trim($_POST['title'] ?? '');
$location    = trim($_POST['location'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$student_id  = trim($_POST['student_id'] ?? '');
$category    = $_POST['category'] ?? '';
$description = trim($_POST['description'] ?? '');

if ($title==='' || $location==='' || $phone==='' || $student_id==='' || $description==='' || ($category!=='Lost' && $category!=='Found')) {
  header('Location: /WebTechnology-Project/Views/Post/create.php?err=1');
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
  header('Location: /WebTechnology-Project/Views/Post/index.php?category=' . urlencode($category) . '&msg=posted');
  exit;
}

header('Location: /WebTechnology-Project/Views/Post/create.php?err=db');
exit;
