<?php
require_once(__DIR__ . '/db.php');

function getAllPosts() {
  $con = getConnection();
  $sql = "SELECT p.id, p.title, p.location, p.phone, p.student_id, p.category, p.description,
                 p.status, p.created_at, p.user_id,
                 COALESCE(u.username, p.posted_by_username) AS posted_by_username,
                 COALESCE(u.email, p.posted_by_email) AS posted_by_email
          FROM posts p
          LEFT JOIN users u ON u.id = p.user_id
          WHERE p.status='approved'
          ORDER BY p.created_at DESC";
  return mysqli_query($con, $sql);
}

function getPostsByCategory($category) {
  $con = getConnection();
  $sql = "SELECT p.id, p.title, p.location, p.phone, p.student_id, p.category, p.description,
                 p.status, p.created_at, p.user_id,
                 COALESCE(u.username, p.posted_by_username) AS posted_by_username,
                 COALESCE(u.email, p.posted_by_email) AS posted_by_email
          FROM posts p
          LEFT JOIN users u ON u.id = p.user_id
          WHERE p.status='approved' AND p.category=?
          ORDER BY p.created_at DESC";

  $stmt = mysqli_prepare($con, $sql);
  if(!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "s", $category);
  mysqli_stmt_execute($stmt);
  return mysqli_stmt_get_result($stmt);
}

function searchPosts($search, $category = null) {
  $con = getConnection();
  $searchTerm = "%$search%";

  if ($category === 'Lost' || $category === 'Found') {
    $sql = "SELECT p.id, p.title, p.location, p.phone, p.student_id, p.category, p.description,
                   p.status, p.created_at, p.user_id,
                   COALESCE(u.username, p.posted_by_username) AS posted_by_username,
                   COALESCE(u.email, p.posted_by_email) AS posted_by_email
            FROM posts p
            LEFT JOIN users u ON u.id = p.user_id
            WHERE p.status='approved'
              AND (p.title LIKE ? OR p.location LIKE ?)
              AND p.category=?
            ORDER BY p.created_at DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $searchTerm, $searchTerm, $category);
  } else {
    $sql = "SELECT p.id, p.title, p.location, p.phone, p.student_id, p.category, p.description,
                   p.status, p.created_at, p.user_id,
                   COALESCE(u.username, p.posted_by_username) AS posted_by_username,
                   COALESCE(u.email, p.posted_by_email) AS posted_by_email
            FROM posts p
            LEFT JOIN users u ON u.id = p.user_id
            WHERE p.status='approved'
              AND (p.title LIKE ? OR p.location LIKE ?)
            ORDER BY p.created_at DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
  }

  mysqli_stmt_execute($stmt);
  return mysqli_stmt_get_result($stmt);
}

function getSmartSuggestions($postId, $title, $location, $category, $limit = 4) {
  $con = getConnection();

  $keywords = preg_split('/\s+/', strtolower($title));
  $likeParts = [];
  $params = [];
  $types = "";

  foreach ($keywords as $word) {
    if (strlen($word) >= 3) {
      $likeParts[] = "title LIKE ?";
      $params[] = "%" . $word . "%";
      $types .= "s";
    }
  }

  if (empty($likeParts)) {
    $likeParts[] = "location LIKE ?";
    $params[] = "%" . $location . "%";
    $types .= "s";
  }

  $sql = "
    SELECT id, title, location, category, created_at
    FROM posts
    WHERE id != ?
      AND status='approved'
      AND category = ?
      AND (" . implode(" OR ", $likeParts) . ")
    ORDER BY created_at DESC
    LIMIT ?
  ";

  $stmt = mysqli_prepare($con, $sql);

  $types = "is" . $types . "i";
  $params = array_merge([$postId, $category], $params, [$limit]);

  mysqli_stmt_bind_param($stmt, $types, ...$params);
  mysqli_stmt_execute($stmt);

  return mysqli_stmt_get_result($stmt);
}

function getPostById($id) {
  $con = getConnection();
  $sql = "SELECT p.*,
                 COALESCE(u.username, p.posted_by_username) AS posted_by_username,
                 COALESCE(u.email, p.posted_by_email) AS posted_by_email
          FROM posts p
          LEFT JOIN users u ON u.id = p.user_id
          WHERE p.id=? AND p.status='approved'
          LIMIT 1";
  $stmt = mysqli_prepare($con, $sql);
  if(!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_assoc($res);
}

function addPost($post) {
  $con = getConnection();

  $sql = "INSERT INTO posts
            (user_id, posted_by_username, posted_by_email,
             title, location, phone, student_id, category, description, status)
          VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($con, $sql);
  if(!$stmt) return false;

  mysqli_stmt_bind_param(
    $stmt,
    "isssssssss",
    $post['user_id'],
    $post['posted_by_username'],
    $post['posted_by_email'],
    $post['title'],
    $post['location'],
    $post['phone'],
    $post['student_id'],
    $post['category'],
    $post['description'],
    $post['status']
  );

  return mysqli_stmt_execute($stmt);
}


function getAllPostsForReview() {
  $con = getConnection();

  $sql = "SELECT p.id, p.user_id, p.posted_by_username, p.posted_by_email,
                 p.title, p.location, p.phone, p.student_id, p.category,
                 p.description, p.status, p.created_at
          FROM posts p
          ORDER BY p.created_at DESC";

  $result = mysqli_query($con, $sql);

  $posts = [];
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $posts[] = $row;
    }
  }
  return $posts;
}
function updatePendingPostStatus($postId, $newStatus) {
  $allowed = ['approved', 'rejected'];
  $newStatus = strtolower(trim($newStatus));

  if (!in_array($newStatus, $allowed, true)) return false;

  $con = getConnection();

  $sql = "UPDATE posts
          SET status=?
          WHERE id=? AND LOWER(status)='pending'";

  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "si", $newStatus, $postId);
  mysqli_stmt_execute($stmt);

  return (mysqli_stmt_affected_rows($stmt) > 0);
}


function updatePostStatus($postId, $newStatus) {
  $allowed = ['pending', 'approved', 'rejected'];
  $newStatus = strtolower(trim($newStatus));

  if (!in_array($newStatus, $allowed, true)) return false;

  $con = getConnection();

  $sql = "UPDATE posts SET status=? WHERE id=?";
  $stmt = mysqli_prepare($con, $sql);
  if (!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "si", $newStatus, $postId);
  return mysqli_stmt_execute($stmt);
}