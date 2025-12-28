<?php
session_start();

if (!isset($_COOKIE['status']) || !isset($_SESSION['user'])) {
  header('location: login.php');
  exit;
}
