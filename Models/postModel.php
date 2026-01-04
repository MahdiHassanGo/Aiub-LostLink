<?php
require_once(__DIR__ . '/db.php');

function getAllPosts() {
  $con = getConnection();
  $sql = "SELECT * FROM posts ORDER BY created_at DESC";
  return mysqli_query($con, $sql);
}
function searchPosts($search, $category = null) {
    $con = getConnection();
    $searchTerm = "%$search%";

    if ($category === 'Lost' || $category === 'Found') {
        $sql = "SELECT * FROM posts WHERE (title LIKE ? OR location LIKE ?) AND category=? ORDER BY created_at DESC";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $searchTerm, $searchTerm, $category);
    } else {
        $sql = "SELECT * FROM posts WHERE title LIKE ? OR location LIKE ? ORDER BY created_at DESC";
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


function getPostsByCategory($category) {
  $con = getConnection();
  $sql = "SELECT * FROM posts WHERE category=? ORDER BY created_at DESC";
  $stmt = mysqli_prepare($con, $sql);
  if(!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "s", $category);
  mysqli_stmt_execute($stmt);
  return mysqli_stmt_get_result($stmt);
}

function getPostById($id) {
  $con = getConnection();
  $sql = "SELECT * FROM posts WHERE id=? LIMIT 1";
  $stmt = mysqli_prepare($con, $sql);
  if(!$stmt) return false;

  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_assoc($res);
}

function addPost($post) {
  $con = getConnection();

  $sql = "INSERT INTO posts (title, location, phone, student_id, category, description)
          VALUES (?, ?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($con, $sql);
  if(!$stmt) return false;

  mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $post['title'],
    $post['location'],
    $post['phone'],
    $post['student_id'],
    $post['category'],
    $post['description']
  );

  return mysqli_stmt_execute($stmt);
}


