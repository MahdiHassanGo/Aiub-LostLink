<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>My Reports</title>

  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0}
    .wrap{width:1050px;max-width:94vw;margin:0 auto}
    .top-inner{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .links a{color:#93c5fd;text-decoration:none;margin-left:10px}
    .links a:hover{text-decoration:underline}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:18px 0}
    .empty{background:#fff;border:1px dashed #bbb;padding:16px;border-radius:12px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;font-size:14px;vertical-align:top}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;background:#e5e7eb}
    .pending{background:#fee2e2}
    .reviewed{background:#fde68a}
    .resolved{background:#dcfce7}
    .rejected{background:#e5e7eb}
    .btn{display:inline-block;padding:8px 10px;border-radius:10px;background:#2c7be5;color:#fff;text-decoration:none;font-size:13px;font-weight:800}
  </style>
</head>

<body>
  <div class="top">
    <div class="wrap top-inner">
      <div><b>AIUB LostLink</b> • My Reports</div>
      <div class="links">
        <a href="/WebTechnology-Project/views/Post/index.php">Posts</a>
        <a href="/WebTechnology-Project/controllers/notificationsCheck.php">Notifications</a>
        <a href="/WebTechnology-Project/controllers/logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="wrap">
    <div class="card">
      <h2 style="margin:0 0 12px;">My Reports</h2>

      <?php if (!isset($reports) || count($reports) === 0): ?>
        <div class="empty">You haven’t reported anything yet.</div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Post</th>
              <th>Reported User</th>
              <th>Reasons</th>
              <th>Status</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reports as $r): ?>
              <?php
                $st = strtolower($r['status']);
                $cls = 'badge';
                if ($st === 'pending') $cls .= ' pending';
                else if ($st === 'reviewed') $cls .= ' reviewed';
                else if ($st === 'resolved') $cls .= ' resolved';
                else $cls .= ' rejected';
              ?>
              <tr>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
                <td>
                  <b><?= htmlspecialchars($r['post_title']) ?></b><br>
                  <a class="btn" href="/WebTechnology-Project/views/Post/details.php?id=<?= (int)$r['post_id'] ?>">View</a>
                </td>
                <td><?= htmlspecialchars($r['reported_username']) ?></td>
                <td><?= htmlspecialchars($r['reasons']) ?></td>
                <td><span class="<?= $cls ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                <td><?= htmlspecialchars($r['details']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
