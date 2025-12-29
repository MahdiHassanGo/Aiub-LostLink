<?php
require_once(__DIR__ . '/db.php');

function addUser($user) {
  $con = getConnection();

  $username = trim($user['username']);
  $email    = trim($user['email']);
  $pass     = $user['password'];

  $hash = password_hash($pass, PASSWORD_DEFAULT);

$sql = "SELECT id, username, email, password_hash, role FROM users WHERE email=? LIMIT 1";
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




/* =========================
   Admin User Management
   ========================= */

function getAllUsers() {
  $con = getConnection();

  // assumes primary key column name is `id`
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

  $sql = "UPDATE users SET role=? WHERE id=?";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "si", $newRole, $userId);
  return mysqli_stmt_execute($stmt);
}
