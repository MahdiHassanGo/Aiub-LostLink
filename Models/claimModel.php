<?php
require_once(__DIR__ . '/db.php');

function addClaim($claim) {
  $con = getConnection();

  $sql = "INSERT INTO claims (post_id, user_id, claimant_name, claimant_phone, message)
          VALUES (?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param(
    $stmt,
    "iisss",
    $claim['post_id'],
    $claim['user_id'],
    $claim['claimant_name'],
    $claim['claimant_phone'],
    $claim['message']
  );

  return mysqli_stmt_execute($stmt);
}

/* ✅ THIS FUNCTION MUST BE HERE */
function getClaimsByUser($userId) {
    $con = getConnection();

    $sql = "
        SELECT 
            c.id AS claim_id,
            c.status,
            c.created_at,
            p.id AS post_id,
            p.title,
            p.category,
            p.location
        FROM claims c
        JOIN posts p ON c.post_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}
