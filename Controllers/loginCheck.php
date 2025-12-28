<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_POST['submit'])) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  die("null email/password");
}

$user = login($email, $password);

if ($user) {
  $_SESSION['user'] = $user;
  setcookie('status', 'true', time() + 3600, '/');

  // ✅ Login শেষে post list এ যাবে
  header('Location: /WebTechnology-Project/Views/Post/index.php');
  exit;
}

die("invalid email/password");
