<?php
session_start();

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('Location: /WebTechnology-Project/Views/Login/login.php');
  exit;
}
