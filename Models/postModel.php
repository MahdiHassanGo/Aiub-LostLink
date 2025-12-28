<?php
require_once('db.php');

function getAllPosts(){
    $con = getConnection();
    $sql = "SELECT * FROM posts ORDER BY created_at DESC";
    $result = mysqli_query($con, $sql);
    return $result; // mysqli_result
}
?>
