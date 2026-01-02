<?php
require_once('../sessionCheck.php');
require_once('../../models/postModel.php');
$isAdmin = strtolower($_SESSION['user']['role'] ?? '') === 'admin';
$search = $_GET['search'] ?? null;


$category = $_GET['category'] ?? null;
$msg = $_GET['msg'] ?? null;

if (($category === 'Lost' || $category === 'Found') && $search) {
    $result = searchPosts($search, $category);
} elseif ($category === 'Lost' || $category === 'Found') {
    $result = getPostsByCategory($category);
} elseif ($search) {
    $result = searchPosts($search);
} else {
    $result = getAllPosts();
}
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
    .top{background:#0f172a;color:#fff;padding:14px 0}
    .wrap{width:1050px;max-width:94vw;margin:0 auto}
    .top-inner{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .links a{color:#93c5fd;text-decoration:none;margin-left:10px}
    .links a:hover{text-decoration:underline}
    .content{padding:18px 0}
    .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    @media(max-width:900px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:600px){.grid{grid-template-columns:1fr}}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px}
    .tag{display:inline-block;font-size:12px;padding:4px 9px;border-radius:999px;background:#eef2ff;margin-bottom:10px}
    .title{margin:0 0 6px;font-size:16px}
    .meta{font-size:13px;color:#444;line-height:1.5;margin:0 0 10px}
    .btn{display:inline-block;padding:8px 10px;border-radius:10px;background:#2c7be5;color:#fff;text-decoration:none;font-size:13px;font-weight:700}
    .btn:hover{background:#1a5fd0}
    .msg{background:#dcfce7;border:1px solid #86efac;color:#166534;padding:10px;border-radius:10px;margin-bottom:12px}
    .empty{background:#fff;border:1px dashed #bbb;padding:16px;border-radius:12px}
    .search{align-items:center;}
  </style>
</head>
<body>
  <div class="top">
    <div class="wrap top-inner">
      <div><b>AIUB LostLink</b> • Posts</div>
      <div class="links">
        <a href="create.php">+ Create Post</a>
        <a href="index.php">All</a>
        <a href="index.php?category=Lost">Lost</a>
        <a href="index.php?category=Found">Found</a>
        <a href="../ClaimRequest/ClaimReq.php">My Claims</a>

<?php if ($isAdmin): ?>
  <a href="../AdminUserManagement/Admin-User-mgt.php">AdminUserManagement</a>
    <a href="../AdminAnalytics/AdminAnalytics.php">AdminAnalytics</a>
<?php endif; ?>
        <a href="../../controllers/logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="wrap content">
    <?php if ($msg === 'posted'): ?>
      <div class="msg">Post submitted successfully ✅</div>
    <?php endif; ?>

    <h2 style="margin:0 0 14px;">
      <?= ($category==='Lost' || $category==='Found') ? htmlspecialchars($category) . " Posts" : "All Posts" ?>
    </h2>

    <?php if (!$result): ?>
      <div class="empty">DB query failed. Check connection/table.</div>
    <?php elseif (mysqli_num_rows($result) === 0): ?>
      <div class="empty">No posts found.</div>
    <?php else: ?>
      <div class="search" >
  <form method="get" style="margin-bottom:14px;">
  <input type="text" name="search" placeholder="Search by title or location" 
         value="<?= htmlspecialchars($search ?? '') ?>" style="padding:6px; border-radius:6px; border:1px solid #ccc;">
  <?php if ($category): ?>
    <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
  <?php endif; ?>
  <button type="submit" style="padding:6px 10px; border:none; border-radius:6px; background:#2c7be5; color:#fff;">Search</button>
</form>

</div>
      <div class="grid">
        
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <div class="card">
            <div class="tag"><?= htmlspecialchars($row['category']) ?></div>
            <h3 class="title"><?= htmlspecialchars($row['title']) ?></h3>
            <p class="meta">
              <b>Location:</b> <?= htmlspecialchars($row['location']) ?><br>
              <b>Posted:</b> <?= htmlspecialchars($row['created_at']) ?>
            </p>
            <a class="btn" href="details.php?id=<?= (int)$row['id'] ?>">View Details</a>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>