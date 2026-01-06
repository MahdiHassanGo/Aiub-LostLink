<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once('../models/userModel.php');
require_once('adminCheck.php');  

if (isset($_POST['submit'])) {
    $userId  = (int)($_POST['user_id'] ?? 0);
    $newRole = $_POST['role'] ?? '';

    $me = (int)($_SESSION['user']['id'] ?? 0);
    if ($me > 0 && $userId === $me) {
        header('location: ../views/AdminUserManagement/Admin-User-mgt.php?msg=cannot_change_self');
        exit;
    }

    if ($userId > 0 && updateUserRole($userId, $newRole)) {
        header('location: ../views/AdminUserManagement/Admin-User-mgt.php?msg=role_updated');
        exit;
    }

    header('location: ../views/AdminUserManagement/Admin-User-mgt.php?msg=update_failed');
    exit;
}

// if someone opens this controller directly
header('location: ../views/AdminUserManagement/Admin-User-mgt.php');
exit;
