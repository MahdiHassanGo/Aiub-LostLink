<?php
require_once(__DIR__ . '/db.php');

function getAllPosts() {
  $con = getConnection();
  $sql = "SELECT * FROM posts ORDER BY created_at DESC";
  return mysqli_query($con, $sql);
}
