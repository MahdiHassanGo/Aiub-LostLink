<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_POST['submit'])) {
  header('location: ../views/Login/signup.php');
  exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$email    = trim($_POST['email'] ?? '');

if ($username === '' || $password === '' || $email === '') {
  echo "null username/password/email";
  exit;
}

$user = ['username' => $username, 'password' => $password, 'email' => $email];

if (addUser($user)) {
  header('location: ../views/Login/login.php');
  exit;
}

echo "Registration failed! (Email may already exist, or DB/table mismatch.)";
