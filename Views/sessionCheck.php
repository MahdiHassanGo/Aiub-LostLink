<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: /WebTechnology-Project/views/Login/login.php');
  exit;
}
