<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../models/announcementModel.php');
$announcement = getActiveAnnouncement();

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

require_once('../../controllers/sessionCheck.php');
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
    body{
      margin:0;
      font-family: Arial, Helvetica, sans-serif;
      min-height:100vh;
      background: linear-gradient(135deg, #5d22ff 0%, #3c0fb0 45%, #240064 100%);
      color:#111;
    }

    .wrap{width:1050px;max-width:94vw;margin:0 auto}
    .content{padding:22px 0 34px}

    /* glass top bar like other pages */
    .top{
      margin-top:18px;
      background: rgba(255,255,255,.12);
      border: 1px solid rgba(255,255,255,.22);
      color:#fff;
      padding:12px 14px;
      border-radius:12px;
      box-shadow: 0 12px 28px rgba(0,0,0,.22);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
    }
    .top-inner{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      flex-wrap:wrap;
    }
    .brand{
      font-weight:900;
      letter-spacing:.2px;
      display:flex;
      gap:8px;
      align-items:center;
    }
    .links{
      display:flex;
      flex-wrap:wrap;
      gap:10px;
      font-size:13px;
    }
    .links a{color:#e9d5ff;text-decoration:none}
    .links a:hover{text-decoration:underline}

    /* main white card container like login/signup */
    .panel{
      margin-top:16px;
      background:#fff;
      border-radius:12px;
      box-shadow: 0 18px 45px rgba(0,0,0,.28);
      padding:18px;
    }

    .headRow{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
      flex-wrap:wrap;
      margin-bottom:14px;
    }
    .pageTitle{
      margin:0;
      font-size:22px;
      font-weight:900;
      color:#3a1695;
    }
    .subTitle{
      margin:6px 0 0;
      font-size:13px;
      color:#666;
    }

    .msg{
      background:#dcfce7;
      border:1px solid #86efac;
      color:#166534;
      padding:10px 12px;
      border-radius:10px;
      margin-bottom:12px;
      font-size:13px;
    }
    .empty{
      background:#fff;
      border:1px dashed #bbb;
      padding:16px;
      border-radius:12px;
      color:#444;
      font-size:14px;
    }

    /* search bar styled */
    .searchBar{
      display:flex;
      gap:10px;
      align-items:center;
      flex-wrap:wrap;
      margin-bottom:14px;
    }
    .searchBar input[type="text"]{
      flex:1;
      min-width:220px;
      padding:10px 12px;
      border:1px solid rgba(0,0,0,.25);
      border-radius:10px;
      outline:none;
      font-size:14px;
    }
    .searchBar input[type="text"]:focus{
      border-color:#6b2cff;
      box-shadow: 0 10px 18px -16px rgba(107,44,255,.75);
    }
    .btn{
      display:inline-block;
      padding:10px 14px;
      border-radius:10px;
      border:0;
      cursor:pointer;
      font-weight:800;
      letter-spacing:.2px;
      color:#fff;
      background: linear-gradient(90deg, #1b103a 0%, #0e0a1e 100%);
      box-shadow: 0 12px 18px rgba(0,0,0,.18);
      text-decoration:none;
      font-size:13px;
      white-space:nowrap;
    }
    .btn:hover{filter:brightness(1.08)}

    /* grid cards */
    .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    @media(max-width:900px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:600px){.grid{grid-template-columns:1fr}}

    .card{
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:12px;
      padding:14px;
      box-shadow: 0 10px 18px -18px rgba(0,0,0,.25);
    }

    .tag{
      display:inline-block;
      font-size:12px;
      padding:4px 10px;
      border-radius:999px;
      margin-bottom:10px;
      font-weight:800;
      border:1px solid transparent;
    }
    .tagLost{background:#fee2e2;border-color:#fecaca;color:#991b1b;}
    .tagFound{background:#dcfce7;border-color:#86efac;color:#166534;}

    .title{margin:0 0 6px;font-size:16px;font-weight:900;color:#111}
    .meta{font-size:13px;color:#444;line-height:1.55;margin:0 0 12px}

    .muted{color:#666;font-size:13px}

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
        <a href="/WebTechnology-Project/Controllers/messagesCheck.php">Messages</a>
        <a href="/WebTechnology-Project/controllers/myReportsCheck.php">My Reports</a>



<?php if ($isAdmin): ?>
  <a href="../AdminUserManagement/Admin-User-mgt.php">AdminUserManagement</a>
    <a href="../AdminAnalytics/AdminAnalytics.php">AdminAnalytics</a>
    <a href="../AdminPostReview/AdminPostReview.php">Admin Post Review</a>
    <a href="/WebTechnology-Project/controllers/adminReportsCheck.php">Reports</a>
<a href="../AdminAnnouncement/AdminAnnouncement.php">Announcement</a>

<?php endif; ?>
        <a href="../../Controllers/notificationsCheck.php">Notifications</a>
        <a href="../../controllers/logout.php">Logout</a>
      </div>
    </div>
<?php if ($announcement && !empty($announcement['message'])): ?>
  <div style="margin:14px auto;width:1050px;max-width:94vw;">
    <div style="background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;padding:10px 12px;border-radius:12px;">
      <b>📢 Announcement:</b> <?php echo esc($announcement['message']); ?>
    </div>
  </div>
<?php endif; ?>

    <div class="content">
      <?php if ($msg === 'posted'): ?>
        <div class="msg">Post submitted successfully ✅</div>
      <?php endif; ?>

      <div class="panel">

        <div class="headRow">
          <div>
            <h2 class="pageTitle">
              <?= ($category==='Lost' || $category==='Found') ? htmlspecialchars($category) . " Posts" : "All Posts" ?>
            </h2>
            <p class="subTitle">Browse approved posts. Use search to filter by title or location.</p>
          </div>
        </div>

        <?php if (!$result): ?>
          <div class="empty">DB query failed. Check connection/table.</div>

        <?php elseif (mysqli_num_rows($result) === 0): ?>
          <div class="empty">No posts found.</div>

        <?php else: ?>

          <form method="get" class="searchBar">
            <input type="text" name="search" placeholder="Search by title or location"
                   value="<?= htmlspecialchars($search ?? '') ?>">

            <?php if ($category): ?>
              <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
            <?php endif; ?>

            <button type="submit" class="btn">Search</button>
          </form>

          <div class="grid">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <?php
                // show ONLY approved
                if (strtolower($row['status'] ?? '') !== 'approved') {
                  continue;
                }
                $cat = strtolower($row['category'] ?? '');
                $tagClass = ($cat === 'lost') ? 'tagLost' : (($cat === 'found') ? 'tagFound' : '');
              ?>
              <div class="card">
                <div class="tag <?= $tagClass ?>"><?= htmlspecialchars($row['category']) ?></div>

                <h3 class="title"><?= htmlspecialchars($row['title']) ?></h3>

                <p class="meta">
                  <b>Location:</b> <?= htmlspecialchars($row['location']) ?><br>
                  <b>Posted:</b> <?= htmlspecialchars($row['created_at']) ?><br>
                  <b>Posted by:</b> <?= htmlspecialchars($row['posted_by_username'] ?? 'Unknown') ?><br>
                </p>

                <a class="btn" href="details.php?id=<?= (int)$row['id'] ?>">View Details</a>
              </div>
            <?php endwhile; ?>
          </div>

          <p class="muted" style="margin:14px 2px 0;">
            Showing approved posts only.
          </p>

        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
