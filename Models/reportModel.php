<?php
require_once('db.php');

function addReport($report) {
  $con = getConnection();

  $postId = (int)($report['post_id'] ?? 0);
  $reporterId = (int)($report['reporter_id'] ?? 0);
  $reportedUserId = (int)($report['reported_user_id'] ?? 0);

  $target = trim($report['target'] ?? '');
  $reasons = trim($report['reasons'] ?? '');
  $details = trim($report['details'] ?? '');

  if ($postId <= 0 || $reporterId <= 0 || $reportedUserId <= 0) return false;
  if ($target === '' || $reasons === '') return false;

  $target = mysqli_real_escape_string($con, $target);
  $reasons = mysqli_real_escape_string($con, $reasons);
  $details = mysqli_real_escape_string($con, $details);

  $sql = "INSERT INTO reports (post_id, reporter_id, reported_user_id, target, reasons, details)
          VALUES ($postId, $reporterId, $reportedUserId, '$target', '$reasons', '$details')";

  return mysqli_query($con, $sql);
}
function getAdminUserIds() {
  $con = getConnection();
  $ids = [];

  $sql = "SELECT id FROM users WHERE LOWER(role)='admin'";
  $res = mysqli_query($con, $sql);

  if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
      $ids[] = (int)$row['id'];
    }
  }

  return $ids;
}

function getReportsByReporter($reporterId) {
  $con = getConnection();
  $reporterId = (int)$reporterId;

  $sql = "SELECT r.id, r.post_id, r.reasons, r.details, r.status, r.created_at,
                 p.title AS post_title,
                 u.username AS reported_username
          FROM reports r
          JOIN posts p ON p.id = r.post_id
          JOIN users u ON u.id = r.reported_user_id
          WHERE r.reporter_id = $reporterId
          ORDER BY r.created_at DESC";

  $res = mysqli_query($con, $sql);
  $list = [];

  if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
      $list[] = $row;
    }
  }

  return $list;
}

function getAllReports() {
  $con = getConnection();

  $sql = "SELECT r.id, r.post_id, r.reasons, r.details, r.status, r.created_at,
                 p.title AS post_title,
                 ru.username AS reporter_username,
                 tu.username AS reported_username
          FROM reports r
          JOIN posts p ON p.id = r.post_id
          JOIN users ru ON ru.id = r.reporter_id
          JOIN users tu ON tu.id = r.reported_user_id
          ORDER BY r.created_at DESC";

  $res = mysqli_query($con, $sql);
  $list = [];

  if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
      $list[] = $row;
    }
  }

  return $list;
}

function updateReportStatus($reportId, $status) {
  $con = getConnection();
  $reportId = (int)$reportId;

  $status = strtolower(trim($status));
  if ($status !== 'pending' && $status !== 'reviewed' && $status !== 'resolved' && $status !== 'rejected') {
    $status = 'pending';
  }

  $status = mysqli_real_escape_string($con, $status);

  $sql = "UPDATE reports SET status='$status' WHERE id=$reportId";
  return mysqli_query($con, $sql);
}

function getReportById($reportId) {
  $con = getConnection();
  $reportId = (int)$reportId;

  $sql = "SELECT * FROM reports WHERE id=$reportId LIMIT 1";
  $res = mysqli_query($con, $sql);

  if ($res) return mysqli_fetch_assoc($res);
  return false;
}
?>