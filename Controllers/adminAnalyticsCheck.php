<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/../models/db.php');

$BASE = '/WebTechnology-Project';

// Admin-only access
$role = strtolower(trim($_SESSION['user']['role'] ?? ''));
if ($role !== 'admin') {
  header("Location: $BASE/views/Post/index.php?msg=unauthorized");
  exit;
}

$dbErrorMsg = '';

// defaults (zero-safe)
$stats = [
  'users_total' => 0,
  'users_user' => 0,
  'users_moderator' => 0,
  'users_admin' => 0,

  'posts_total' => 0,
  'posts_lost' => 0,
  'posts_found' => 0,
];

$charts = [
  'users_role_labels' => ['User', 'Moderator', 'Admin'],
  'users_role_data'   => [0, 0, 0],

  'posts_cat_labels'  => ['Lost', 'Found'],
  'posts_cat_data'    => [0, 0],

  'trend_labels'      => [],   // last 7 days labels
  'trend_data'        => [],   // last 7 days counts
];

// small helper: returns int count or 0 if query fails
function queryCount($con, $sql) {
  $res = mysqli_query($con, $sql);
  if (!$res) return 0;
  $row = mysqli_fetch_row($res);
  return $row ? (int)$row[0] : 0;
}

$con = getConnection();

/* -----------------------
   USERS COUNTS
------------------------ */
$stats['users_total']     = queryCount($con, "SELECT COUNT(*) FROM users");
$stats['users_admin']     = queryCount($con, "SELECT COUNT(*) FROM users WHERE LOWER(role)='admin'");
$stats['users_moderator'] = queryCount($con, "SELECT COUNT(*) FROM users WHERE LOWER(role)='moderator'");

// treat NULL/empty role as User too
$stats['users_user']      = queryCount($con, "SELECT COUNT(*) FROM users WHERE role IS NULL OR role='' OR LOWER(role)='user'");

$charts['users_role_data'] = [
  (int)$stats['users_user'],
  (int)$stats['users_moderator'],
  (int)$stats['users_admin'],
];

/* -----------------------
   POSTS COUNTS
------------------------ */
$stats['posts_total'] = queryCount($con, "SELECT COUNT(*) FROM posts");
$stats['posts_lost']  = queryCount($con, "SELECT COUNT(*) FROM posts WHERE LOWER(category)='lost'");
$stats['posts_found'] = queryCount($con, "SELECT COUNT(*) FROM posts WHERE LOWER(category)='found'");

$charts['posts_cat_data'] = [
  (int)$stats['posts_lost'],
  (int)$stats['posts_found'],
];

/* -----------------------
   POSTS TREND (Last 7 Days)
   Always returns 7 points
------------------------ */
$map = [];     // 'YYYY-MM-DD' => count
$labels = [];  // 'Jan 02'
$values = [];  // counts

for ($i = 6; $i >= 0; $i--) {
  $d = date('Y-m-d', strtotime("-$i day"));
  $map[$d] = 0;
  $labels[] = date('M d', strtotime($d));
}

// grouped by date(created_at)
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
    $d = $row['d'];
    $c = (int)$row['c'];
    if (isset($map[$d])) $map[$d] = $c;
  }
} else {
  // trend query failed but keep zeros and show message
  $dbErrorMsg = "Trend query failed (showing zeros).";
}

foreach ($map as $d => $c) {
  $values[] = (int)$c;
}

$charts['trend_labels'] = $labels;
$charts['trend_data']   = $values;

// final zero-safe guard
if (count($charts['trend_labels']) !== 7) $charts['trend_labels'] = ['No data'];
if (count($charts['trend_data']) !== 7)   $charts['trend_data']   = [0];
