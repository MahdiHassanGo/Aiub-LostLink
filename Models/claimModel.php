<?php
require_once(__DIR__ . '/db.php');

function addClaim($claim) {
  $con = getConnection();

  $sql = "INSERT INTO claims (post_id, user_id, claimant_name, claimant_phone, message)
          VALUES (?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  // user_id can be NULL
  $userId = $claim['user_id'];
  mysqli_stmt_bind_param(
    $stmt,
    "iisss",
    $claim['post_id'],
    $userId,
    $claim['claimant_name'],
    $claim['claimant_phone'],
    $claim['message']
  );

  return mysqli_stmt_execute($stmt);
}
