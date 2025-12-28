<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_POST['submit'])) {
  header('location: ../views/Loginlogin.php');
  exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  echo "null email/password";
  exit;
}

$user = login($email, $password);

if ($user) {
  $_SESSION['user'] = $user;
  setcookie('status', 'true', time() + 3600, '/'); // 1 hour
  header('location: ../views/Post/ShowPost.php');
  exit;
}

echo "invalid email/password";
