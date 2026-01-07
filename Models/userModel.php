<?php
require_once(__DIR__ . '/db.php');

/* =========================
   User Register & Login
   ========================= */


   // Role user 

function addUser($user) {
  $con = getConnection();

  $username = trim($user['username'] ?? '');
  $email    = trim($user['email'] ?? '');
  $pass     = $user['password'] ?? '';

  if ($username === '' || $email === '' || $pass === '') return false;

  // Check duplicate email
  $checkSql  = "SELECT id FROM users WHERE email=? LIMIT 1";
  $checkStmt = mysqli_prepare($con, $checkSql);
  if (!$checkStmt) return false;

  mysqli_stmt_bind_param($checkStmt, "s", $email);
  mysqli_stmt_execute($checkStmt);
  $checkRes = mysqli_stmt_get_result($checkStmt);

  if ($checkRes && mysqli_fetch_assoc($checkRes)) {
    return false; // email already exists
  }

  $hash = password_hash($pass, PASSWORD_DEFAULT);

  // Insert new user (default role User)
  $sql  = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'User')";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);
  return mysqli_stmt_execute($stmt);
}

function login($email, $password) {
  $con = getConnection();

  $sql  = "SELECT * FROM users WHERE email=? LIMIT 1";
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

/* =========================
   Forgot Password Helpers
   ========================= */

function getUserByEmail($email){
  $con = getConnection();

  $sql  = "SELECT * FROM users WHERE email=? LIMIT 1";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);

  $res = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_assoc($res);
}

function updatePasswordByEmail($email, $newPlainPassword){
  $con = getConnection();
  $hash = password_hash($newPlainPassword, PASSWORD_DEFAULT);

  // ✅ DB column is password_hash
  $sql  = "UPDATE users SET password_hash=? WHERE email=?";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "ss", $hash, $email);
  return mysqli_stmt_execute($stmt);
}

/* =========================
   Admin User Management
   ========================= */

function getAllUsers() {
  $con = getConnection();

  $sql = "SELECT id, username, email, role FROM users ORDER BY id DESC";
  $result = mysqli_query($con, $sql);

  $users = [];
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $users[] = $row;
    }
  }
  return $users;
}

function updateUserRole($userId, $newRole) {
  $allowed = ['User', 'Admin', 'Moderator'];
  if (!in_array($newRole, $allowed, true)) return false;

  $con = getConnection();

  $sql  = "UPDATE users SET role=? WHERE id=?";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "si", $newRole, $userId);
  return mysqli_stmt_execute($stmt);
}
