<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_COOKIE['status'])) {
    header('location: ../views/Login/login.php');
    exit;
}
