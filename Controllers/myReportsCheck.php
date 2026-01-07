<?php
session_start();
require_once('sessionCheck.php');
require_once('../Models/reportModel.php');

if (!isset($_SESSION['user'])) {
  header('location: ../views/Login/login.php');
  exit;
}

$userId = (int)($_SESSION['user']['id'] ?? 0);
if ($userId <= 0) {
  header('location: ../views/Login/login.php');
  exit;
}

$reports = getReportsByReporter($userId);

require_once('../Views/Report/myReports.php');
?>