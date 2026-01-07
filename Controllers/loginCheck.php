<?php
session_start();

// ✅ Error show (development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../models/userModel.php');

// ✅ notification model (case-safe + optional)
$notifPath = __DIR__ . '/../models/notificationModel.php';
if (file_exists($notifPath)) {
  require_once($notifPath);
}

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

  // ✅ notification function থাকলে call হবে
  if (function_exists('addNotification')) {
    addNotification(
      $_SESSION['user']['id'],
      'info',
      'Login successful',
      'You just logged in.',
      '/WebTechnology-Project/views/HomePage/homepage.php'
    );
  }

  // ✅ Only ONE redirect
  header('Location: ../views/Post/index.php');
  exit;
}

header('Location: ../views/Login/login.php?msg=invalid');
exit;
