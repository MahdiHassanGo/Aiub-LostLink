<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../models/userModel.php');
require_once('../models/notificationModel.php');

if (!isset($_POST['submit'])) {
  header('Location: ../views/Login/login.php');
  exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  header('Location: ../views/Login/login.php?msg=empty');
  exit;
}

$user = login($email, $password);

if ($user) {
 
  $_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
    'role' => $user['role']
  ];

  setcookie('status', 'true', time() + 3600, '/');

 
  addNotification($_SESSION['user']['id'], 'info', 'Login successful', 'You just logged in.', '/WebTechnology-Project/views/HomePage/homepage.php');

  header('Location: /WebTechnology-Project/views/Post/index.php');
  header('Location: ../views/Post/index.php');
  exit;
}

header('Location: ../views/Login/login.php?msg=invalid');
exit;
