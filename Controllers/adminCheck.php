<?php
require_once(__DIR__ . '/sessionCheck.php');

$role = strtolower(trim($_SESSION['user']['role'] ?? ''));

if ($role !== 'admin') {
    header('location: ../views/Post/index.php?msg=unauthorized');
    exit;
}
