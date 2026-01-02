<?php
require_once('../sessionCheck.php');
require_once('../../controllers/adminAnalyticsCheck.php');

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Admin Analytics</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0;position:relative}
    .wrap{width:1100px;max-width:94vw;margin:0 auto}
    h1{margin:0;font-size:20px}
    .sub{font-size:12px;color:#cbd5e1;margin-top:4px}
    .back{position:absolute;top:18px;right:24px;font-size:14px}
    .back a{color:#38bdf8;text-decoration:none}
    .back a:hover{text-decoration:underline}

    .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 8px 22px rgba(15,23,42,.06);padding:16px;margin:18px 0}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px 12px;border-bottom:1px solid #eef2f7;text-align:left}
    th{font-size:13px;color:#334155;background:#f8fafc}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;border:1px solid #e2e8f0;background:#f8fafc}
  </style>
</head>
<body>

<div class="top">
  <div class="wrap">
    <h1>Admin Analytics</h1>
    <div class="sub">Simple analytics (tables). Live DB values. Zero-safe.</div>
  </div>
  <div class="back">
    Back to <a href="/WebTechnology-Project/views/Post/index.php">Home</a>
  </div>
</div>

<div class="wrap">

  <div class="card">
    <h3 style="margin:0 0 12px;">Summary</h3>
    <table>
      <thead>
        <tr>
          <th>Metric</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Total Users</td><td><span class="badge"><?php echo (int)$stats['users_total']; ?></span></td></tr>
        <tr><td>Users (Role: User)</td><td><span class="badge"><?php echo (int)$stats['users_user']; ?></span></td></tr>
        <tr><td>Users (Role: Moderator)</td><td><span class="badge"><?php echo (int)$stats['users_moderator']; ?></span></td></tr>
        <tr><td>Users (Role: Admin)</td><td><span class="badge"><?php echo (int)$stats['users_admin']; ?></span></td></tr>

        <tr><td>Total Posts</td><td><span class="badge"><?php echo (int)$stats['posts_total']; ?></span></td></tr>
        <tr><td>Posts (Lost)</td><td><span class="badge"><?php echo (int)$stats['posts_lost']; ?></span></td></tr>
        <tr><td>Posts (Found)</td><td><span class="badge"><?php echo (int)$stats['posts_found']; ?></span></td></tr>
      </tbody>
    </table>
  </div>

 
</div>
</body>
</html>
