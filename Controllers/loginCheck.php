<?php
session_start();
require_once('../models/userModel.php');

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
  // Make session structure consistent (important for admin role checks)
  $_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
    'role' => $user['role']
  ];

  setcookie('status', 'true', time() + 3600, '/');

  header('Location: ../views/Post/index.php');
  exit;
}

header('Location: ../views/Login/login.php?msg=invalid');
exit;
