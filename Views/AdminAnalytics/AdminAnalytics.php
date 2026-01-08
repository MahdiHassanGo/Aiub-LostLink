<?php
require_once( '../../controllers/sessionCheck.php');
require_once('../../controllers/adminAnalyticsCheck.php');

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Admin Analytics</title>
  <link rel="stylesheet" href="../AdminAnalytics/AdminAnalytics.css" />
  
</head>
<body>

<div class="top">
  <div class="wrap">
    <h1>Admin Analytics</h1>
    <div class="sub">Simple analytics (tables). Live DB values. Zero-safe.</div>
  </div>
  <div class="back">
    Back to <a href="../../views/Post/index.php">Home</a>
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
