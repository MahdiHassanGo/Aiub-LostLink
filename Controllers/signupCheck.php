<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_POST['submit'])) {
  header('Location: /WebTechnology-Project/Views/Login/signup.php');
  exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$email    = trim($_POST['email'] ?? '');

if ($username === '' || $password === '' || $email === '') {
  die("null username/password/email");
}

$user = ['username' => $username, 'password' => $password, 'email' => $email];

if (addUser($user)) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}

die("Registration failed! (Email may already exist.)");
