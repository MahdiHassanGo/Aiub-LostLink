<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// load correct model file (supports both names to avoid 500)
$model1 = __DIR__ . '/../models/userModel.php';
$model2 = __DIR__ . '/../models/userModels.php';
if (file_exists($model1)) require_once($model1);
else require_once($model2);

$BASE = '/WebTechnology-Project';

$role = $_SESSION['user']['role'] ?? '';
$role = strtolower(trim($role));

if ($role !== 'admin') {
  header("Location: $BASE/views/Post/index.php?msg=unauthorized");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId  = (int)($_POST['user_id'] ?? 0);
  $newRole = $_POST['role'] ?? '';

  $me = $_SESSION['user']['id'] ?? null;
  if ($me !== null && $userId === (int)$me) {
    header("Location: $BASE/views/AdminUserManagement/Admin-User-mgt.php?msg=cannot_change_self");
    exit;
  }

  if ($userId > 0 && updateUserRole($userId, $newRole)) {
    header("Location: $BASE/views/AdminUserManagement/Admin-User-mgt.php?msg=role_updated");
    exit;
  }

  header("Location: $BASE/views/AdminUserManagement/Admin-User-mgt.php?msg=update_failed");
  exit;
}

$users = getAllUsers();
