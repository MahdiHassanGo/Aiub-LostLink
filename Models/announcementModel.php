<?php
require_once(__DIR__ . '/db.php');

function getActiveAnnouncement() {
  $con = getConnection();

  $sql = "SELECT id, message, is_active, created_at
          FROM announcements
          WHERE is_active=1
          ORDER BY id DESC
          LIMIT 1";

  $res = mysqli_query($con, $sql);
  if (!$res) return false;

  return mysqli_fetch_assoc($res);
}

function getLatestAnnouncement() {
  $con = getConnection();

  $sql = "SELECT id, message, is_active, created_at
          FROM announcements
          ORDER BY id DESC
          LIMIT 1";

  $res = mysqli_query($con, $sql);
  if (!$res) return false;

  return mysqli_fetch_assoc($res);
}

function setAnnouncement($message, $makeActive, $createdBy = null) {
  $con = getConnection();

  $message = trim((string)$message);
  if ($message === '') return false;

  $makeActive = $makeActive ? 1 : 0;

  if ($makeActive === 1) {
    mysqli_query($con, "UPDATE announcements SET is_active=0");
  }

  $sql = "INSERT INTO announcements (message, is_active, created_by)
          VALUES (?, ?, ?)";

  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  $createdBy = ($createdBy !== null) ? (int)$createdBy : null;

  if ($createdBy === null) $createdBy = 0;

  mysqli_stmt_bind_param($stmt, "sii", $message, $makeActive, $createdBy);
  return mysqli_stmt_execute($stmt);
}

function deactivateAnnouncement() {
  $con = getConnection();
  return mysqli_query($con, "UPDATE announcements SET is_active=0");
}
