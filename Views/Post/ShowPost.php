<?php
require_once('sessionCheck.php');
require_once('../models/postModel.php');


$result = getAllPosts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Posts</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .topbar{background:#0f172a;color:#fff;padding:14px 0}
    .wrap{width:1050px;max-width:94vw;margin:0 auto}
    .topbar-inner{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .brand{font-weight:800;letter-spacing:.3px}
    .user{font-size:14px;opacity:.95}
    .btn{background:#2c7be5;color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;font-size:14px;font-weight:600}
    .btn:hover{background:#1a5fd0}

    .content{padding:22px 0}
    .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    @media(max-width:900px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:600px){.grid{grid-template-columns:1fr}}

    .card{background:#fff;border:1px solid #e2e2e2;border-radius:12px;padding:14px}
    .tag{display:inline-block;font-size:12px;padding:4px 9px;border-radius:999px;background:#eef2ff;margin-bottom:10px}
    .title{margin:0 0 6px;font-size:16px}
    .meta{font-size:13px;color:#444;line-height:1.5;margin:0 0 10px}
    .desc{font-size:13px;color:#333;line-height:1.5;margin:0}
    .muted{color:#666;font-size:12px;margin-top:10px}
    .empty{background:#fff;border:1px dashed #bbb;padding:16px;border-radius:12px}
  </style>
</head>
<body>
  <div class="topbar">
    <div class="wrap topbar-inner">
      <div class="brand">AIUB LostLink</div>
      <div class="user">
        Logged in as: <b><?= htmlspecialchars($_SESSION['user']['username']) ?></b>
      </div>
      <a class="btn" href="../controllers/logout.php">Logout</a>
    </div>
  </div>

  <div class="wrap content">
    <h2 style="margin:0 0 14px;">Latest Lost & Found Posts</h2>

    <?php if (!$result): ?>
      <div class="empty">
        DB query failed. Check table name/columns and DB connection.
      </div>
    <?php else: ?>

      <?php if (mysqli_num_rows($result) === 0): ?>
        <div class="empty">No posts found.</div>
      <?php else: ?>
        <div class="grid">
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
              <div class="tag"><?= htmlspecialchars($row['category']) ?></div>
              <h3 class="title"><?= htmlspecialchars($row['title']) ?></h3>
              <p class="meta">
                <b>Location:</b> <?= htmlspecialchars($row['location']) ?><br>
                <b>Phone:</b> <?= htmlspecialchars($row['phone']) ?><br>
                <b>Student ID:</b> <?= htmlspecialchars($row['student_id']) ?><br>
              </p>
              <p class="desc"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
              <div class="muted">Posted: <?= htmlspecialchars($row['created_at']) ?></div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>

    <?php endif; ?>
  </div>
</body>
</html>