S<?php
session_start();

// Protect page (must be logged in)
if(!isset($_COOKIE['status']) || $_COOKIE['status'] !== 'true'){
    header('location: login.php');
    exit;
}

// If session user not found, force login again
if(!isset($_SESSION['user'])){
    header('location: login.php');
    exit;
}

$user = $_SESSION['user']; // array from DB: username, email, role, etc.
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

    .grid{display:grid;grid-template-columns: 1fr 1fr;gap:14px;margin-top:16px}
    .card{background:#fff;border:1px solid #e6e8f0;border-radius:14px;padding:14px;box-shadow:0 10px 25px rgba(2,6,23,.06)}
    .title{margin:0 0 8px}
    .muted{color:#555;margin:0;line-height:1.45}
    .chip{display:inline-block;padding:6px 10px;border-radius:999px;background:#eef2ff;color:#1e3a8a;font-size:12px;margin-top:8px}
    .btn{border:0;padding:10px 12px;border-radius:10px;background:#0ea5e9;color:#fff;cursor:pointer;font-weight:600;text-decoration:none;display:inline-block}
    .btn:hover{opacity:.95}
    .btn.ghost{background:#fff;color:#0f172a;border:1px solid #d6d9e6}
    .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px}
    .full{grid-column:1 / -1}
  </style>
</head>
<body>

  <div class="nav">
    <div class="container nav-inner">
      <div class="brand">Lost & Found</div>
      <div class="nav-links">
        <a href="./homepage.php">Home</a>
        <a href="../controllers/logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="grid">

      <div class="card full">
        <h2 class="title">Welcome, <?php echo htmlspecialchars($user['username']); ?> 👋</h2>
        <p class="muted">
          You are logged in with <b><?php echo htmlspecialchars($user['email']); ?></b>.
        </p>
        <span class="chip">Role: <?php echo htmlspecialchars($user['role']); ?></span>
      </div>

      <div class="card">
        <h3 class="title">Create a Post</h3>
        <p class="muted">Post lost/found items with title, location, phone, category and description.</p>
        <div class="actions">
          <a class="btn" href="../Post/create.php">New Post</a>
         
        </div>
      </div>

      <div class="card">
        <h3 class="title">Browse Posts</h3>
        <p class="muted">See all lost/found posts posted by others.</p>
        <div class="actions">
          <a class="btn" href="../Post/index.php">View All</a>
        
        </div>
      </div>

    </div>
  </div>

</body>
</html>
