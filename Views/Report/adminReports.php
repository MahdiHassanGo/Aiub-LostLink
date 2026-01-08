<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Reports (Admin)</title>

  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0}
    .wrap{width:1200px;max-width:96vw;margin:0 auto}
    .top-inner{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .links a{color:#93c5fd;text-decoration:none;margin-left:10px}
    .links a:hover{text-decoration:underline}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:18px 0}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;font-size:14px;vertical-align:top}
    select{padding:8px;border-radius:10px;border:1px solid #bbb}
    .btn{display:inline-block;padding:8px 10px;border-radius:10px;background:#2c7be5;color:#fff;text-decoration:none;font-size:13px;font-weight:800;border:0;cursor:pointer}
    .btn:hover{background:#1a5fd0}
    .empty{background:#fff;border:1px dashed #bbb;padding:16px;border-radius:12px}
  </style>
</head>

<body>
  <div class="top">
    <div class="wrap top-inner">
      <div><b>AIUB LostLink</b> • Reports</div>
      <div class="links">
        <a href="/WebTechnology-Project/views/Post/index.php">Posts</a>
        <a href="/WebTechnology-Project/controllers/logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="wrap">
    <div class="card">
      <h2 style="margin:0 0 12px;">All Reports</h2>

      <?php if (!isset($reports) || count($reports) === 0): ?>
        <div class="empty">No reports yet.</div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Post</th>
              <th>Reporter</th>
              <th>Reported</th>
              <th>Reasons</th>
              <th>Details</th>
              <th>Status</th>
              <th>Update</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reports as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
                <td>
                  <b><?= htmlspecialchars($r['post_title']) ?></b><br>
                  <a class="btn" href="/WebTechnology-Project/views/Post/details.php?id=<?= (int)$r['post_id'] ?>">View</a>
                </td>
                <td><?= htmlspecialchars($r['reporter_username']) ?></td>
                <td><?= htmlspecialchars($r['reported_username']) ?></td>
                <td><?= htmlspecialchars($r['reasons']) ?></td>
                <td><?= htmlspecialchars($r['details']) ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
                <td>
                  <form method="post" action="/WebTechnology-Project/controllers/adminReportsCheck.php">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <select name="status">
                      <option value="pending"  <?= ($r['status']==='pending')?'selected':'' ?>>pending</option>
                      <option value="reviewed" <?= ($r['status']==='reviewed')?'selected':'' ?>>reviewed</option>
                      <option value="resolved" <?= ($r['status']==='resolved')?'selected':'' ?>>resolved</option>
                      <option value="rejected" <?= ($r['status']==='rejected')?'selected':'' ?>>rejected</option>
                    </select>
                    <button class="btn" type="submit">Save</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>