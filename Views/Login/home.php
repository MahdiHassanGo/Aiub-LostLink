<?php
session_start();

if(!isset($_COOKIE['status']) || $_COOKIE['status'] !== 'true'){
    header('location: login.php');
    exit;
}
if(!isset($_SESSION['user'])){
    header('location: login.php');
    exit;
}

require_once('../models/postModel.php');
$posts = getAllPosts();
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home | Lost & Found</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .nav{background:#0f172a;padding:14px 0;color:#fff}
    .container{width:1050px;max-width:1050px;margin:0 auto;padding:0 16px}
    .nav-inner{display:flex;align-items:center;justify-content:space-between}
    .brand{font-weight:700;letter-spacing:.3px}
    .nav-links a{color:#fff;text-decoration:none;display:inline-block;margin-left:10px;padding:8px 12px;border-radius:10px;background:rgba(255,255,255,.10)}
    .nav-links a:hover{background:rgba(255,255,255,.16)}
    .grid{display:grid;grid-template-columns: repeat(3, 1fr);gap:14px;margin:16px 0 40px}
    .card{background:#fff;border:1px solid #e6e8f0;border-radius:14px;padding:14px;box-shadow:0 10px 25px rgba(2,6,23,.06)}
    .top{display:flex;align-items:center;justify-content:space-between;gap:10px}
    .badge{padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700}
    .lost{background:#fee2e2;color:#991b1b}
    .found{background:#dcfce7;color:#166534}
    .muted{color:#555;margin:6px 0 0;line-height:1.45}
    .meta{color:#666;font-size:12px;margin-top:10px}
    .header{margin-top:16px;background:#fff;border:1px solid #e6e8f0;border-radius:14px;padding:14px;box-shadow:0 10px 25px rgba(2,6,23,.06)}
    .header h2{margin:0 0 6px}
    .btn{border:0;padding:10px 12px;border-radius:10px;background:#0ea5e9;color:#fff;cursor:pointer;font-weight:600;text-decoration:none;display:inline-block}
    .btn:hover{opacity:.95}
  </style>
</head>
<body>

  <div class="nav">
    <div class="container nav-inner">
      <div class="brand">Lost & Found</div>
      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="createPost.php">New Post</a>
        <a href="../controllers/logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="container">

    <div class="header">
      <h2>All Lost & Found Items</h2>
      <p class="muted">Welcome, <b><?php echo htmlspecialchars($user['username']); ?></b> (<?php echo htmlspecialchars($user['email']); ?>)</p>
    </div>

    <div class="grid">
      <?php if(mysqli_num_rows($posts) == 0){ ?>
        <div class="card" style="grid-column:1/-1;">
          <b>No posts yet.</b>
          <p class="muted">Click “New Post” to add a lost/found item.</p>
        </div>
      <?php } ?>

      <?php while($row = mysqli_fetch_assoc($posts)){ ?>
        <div class="card">
          <div class="top">
            <h3 style="margin:0"><?php echo htmlspecialchars($row['title']); ?></h3>
            <?php if($row['category'] === 'Lost'){ ?>
              <span class="badge lost">LOST</span>
            <?php } else { ?>
              <span class="badge found">FOUND</span>
            <?php } ?>
          </div>

          <p class="muted"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

          <div class="meta">
            <div><b>Location:</b> <?php echo htmlspecialchars($row['location']); ?></div>
            <div><b>Phone:</b> <?php echo htmlspecialchars($row['phone']); ?></div>
            <div><b>Student ID:</b> <?php echo htmlspecialchars($row['student_id']); ?></div>
            <div><b>Posted:</b> <?php echo htmlspecialchars($row['created_at']); ?></div>
          </div>
        </div>
      <?php } ?>
    </div>

  </div>
</body>
</html>
