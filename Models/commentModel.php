<?php

function addComment($postId, $userId, $username, $comment) {
  $con = getConnection();

  $postId = (int)$postId;
  $userId = (int)$userId;

  $username = trim($username);
  $comment = trim($comment);

  if ($postId <= 0 || $userId <= 0 || $username === '' || $comment === '') {
    return false;
  }

  $username = mysqli_real_escape_string($con, $username);
  $comment = mysqli_real_escape_string($con, $comment);

  $sql = "INSERT INTO comments (post_id, user_id, username, comment)
          VALUES ($postId, $userId, '$username', '$comment')";
  return mysqli_query($con, $sql);
}

function getCommentsByPostId($postId) {
  $con = getConnection();
  $postId = (int)$postId;

  $sql = "SELECT id, post_id, user_id, username, comment, created_at
          FROM comments
          WHERE post_id = $postId
          ORDER BY created_at DESC";

  $result = mysqli_query($con, $sql);
  $list = [];

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $list[] = $row;
    }
  }

  return $list;
}
