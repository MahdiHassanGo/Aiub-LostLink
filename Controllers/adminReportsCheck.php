<?php
session_start();
require_once('adminCheck.php');
require_once('../Models/reportModel.php');
require_once('../Models/notificationModel.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)($_POST['id'] ?? 0);
  $status = $_POST['status'] ?? 'pending';

  if ($id > 0) {
    $old = getReportById($id);

    if (updateReportStatus($id, $status)) {
      if ($old && isset($old['reporter_id'])) {
        $reporterId = (int)$old['reporter_id'];
        addNotification(
          $reporterId,
          'report',
          'Report status updated',
          'Admin updated your report status.',
          '/WebTechnology-Project/controllers/myReportsCheck.php'
        );
      }
    }
  }

  header('location: /WebTechnology-Project/controllers/adminReportsCheck.php');
  exit;
}

$reports = getAllReports();
require_once('../Views/Report/adminReports.php');
?>