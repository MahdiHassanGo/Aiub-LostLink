<?php
require_once('../models/userModel.php');

if(!isset($_POST['submit'])){
  header('location: ../Views/Login/forgotPassword.php');
  exit;
}

$email = trim($_POST['email'] ?? '');
$new  = $_POST['new_password'] ?? '';
$conf = $_POST['confirm_password'] ?? '';

if($email === '' || $new === '' || $conf === ''){
  header('location: ../Views/Login/forgotPassword.php?err=All fields are required');
  exit;
}

if($new !== $conf){
  header('location: ../Views/Login/forgotPassword.php?err=Passwords do not match');
  exit;
}

$user = getUserByEmail($email);
if(!$user){
  header('location: ../Views/Login/forgotPassword.php?err=Email not found');
  exit;
}

/*
  IMPORTANT:
  যদি তোমার প্রোজেক্টে password plain text রাখা হয় (student projects এ অনেক সময় হয়),
  তাহলে নিচের line 그대로 রাখো।
  আর যদি password_hash/password_verify ব্যবহার করো, তাহলে $new এর জায়গায় hash দাও।
*/

// Option A (plain text - matches many basic login systems)
$ok = updatePasswordByEmail($email, $new);

// Option B (secure - use this only if loginCheck.php uses password_verify)
// $hash = password_hash($new, PASSWORD_DEFAULT);
// $ok = updatePasswordByEmail($email, $hash);

if($ok){
  header('location: ../Views/Login/login.php?msg=Password updated. Please login.');
  exit;
}

header('location: ../Views/Login/forgotPassword.php?err=Failed to update password');
exit;
