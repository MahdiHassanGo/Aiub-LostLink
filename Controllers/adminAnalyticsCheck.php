<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/../models/db.php');

$BASE = '/WebTechnology-Project';

$role = strtolower(trim($_SESSION['user']['role'] ?? ''));
if ($role !== 'admin') {
  header("Location: $BASE/views/Post/index.php?msg=unauthorized");
  exit;
}

// default values (zero-safe)
$stats = [
  'users_total' => 0,
  'users_user' => 0,
  'users_moderator' => 0,
  'users_admin' => 0,
  'posts_total' => 0,
  'posts_lost' => 0,
  'posts_found' => 0,
];

$trend = []; // last 7 days rows: [date_label, count]

function queryCount($con, $sql) {
  $res = mysqli_query($con, $sql);
  if (!$res) return 0;
  $row = mysqli_fetch_row($res);
  return $row ? (int)$row[0] : 0;
}

$con = getConnection();

/* USERS */
$stats['users_total']     = queryCount($con, "SELECT COUNT(*) FROM users");
$stats['users_admin']     = queryCount($con, "SELECT COUNT(*) FROM users WHERE LOWER(role)='admin'");
$stats['users_moderator'] = queryCount($con, "SELECT COUNT(*) FROM users WHERE LOWER(role)='moderator'");
$stats['users_user']      = queryCount($con, "SELECT COUNT(*) FROM users WHERE role IS NULL OR role='' OR LOWER(role)='user'");

/* POSTS */
$stats['posts_total'] = queryCount($con, "SELECT COUNT(*) FROM posts");
$stats['posts_lost']  = queryCount($con, "SELECT COUNT(*) FROM posts WHERE LOWER(category)='lost'");
$stats['posts_found'] = queryCount($con, "SELECT COUNT(*) FROM posts WHERE LOWER(category)='found'");


$map = [];
for ($i = 6; $i >= 0; $i--) {
  $d = date('Y-m-d', strtotime("-$i day"));
  $map[$d] = 0;
}

$sqlTrend = "
  SELECT DATE(created_at) AS d, COUNT(*) AS c
  FROM posts
  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
  GROUP BY DATE(created_at)
  ORDER BY d ASC
";
$res = mysqli_query($con, $sqlTrend);
if ($res) {
  while ($row = mysqli_fetch_assoc($res)) {
    if (isset($map[$row['d']])) $map[$row['d']] = (int)$row['c'];
  }
}

foreach ($map as $d => $c) {
  $trend[] = [date('M d', strtotime($d)), (int)$c];
}
