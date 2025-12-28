<?php
require_once(__DIR__ . '/db.php');

function addUser($user) {
  $con = getConnection();

  $username = trim($user['username']);
  $email    = trim($user['email']);
  $pass     = $user['password'];

  $hash = password_hash($pass, PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'User')";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);
  return mysqli_stmt_execute($stmt);
}

function login($email, $password) {
  $con = getConnection();

  $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);

  if ($row && password_verify($password, $row['password_hash'])) {
    return $row;
  }
  return false;
}
