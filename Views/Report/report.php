<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Report</title>

  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .nav{background:#0f172a;padding:14px 0;color:#fff}
    .container{width:1050px;max-width:1050px;margin:0 auto;padding:0 16px}
    .nav-inner{display:flex;align-items:center;justify-content:space-between}
    .brand{font-weight:700;letter-spacing:.3px}
    .nav-links a{color:#fff;text-decoration:none;display:inline-block;margin-left:10px;padding:8px 12px;border-radius:10px;background:rgba(255,255,255,.10)}
    .nav-links a:hover{background:rgba(255,255,255,.16)}

    .page{padding:22px 0}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px}
    h1{margin:0 0 8px;font-size:22px}
    .sub{margin:0 0 14px;color:#444;font-size:14px}
    label{display:block;margin:10px 0 6px;font-size:14px}
    textarea{width:100%;padding:10px;border:1px solid #bbb;border-radius:10px;min-height:90px;resize:vertical}
    .row{margin:10px 0}
    .btn{border:0;cursor:pointer;padding:10px 12px;border-radius:10px;background:#0f172a;color:#fff;font-size:14px}
    .btn.secondary{background:#e5e7eb;color:#111}
    .err{background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:10px;border-radius:10px;margin-bottom:12px}
    .box{border:1px solid #e5e7eb;border-radius:12px;padding:12px;margin:12px 0;background:#fff}
  </style>
</head>

<body>
  <div class="nav">
    <div class="container">
      <div class="nav-inner">
        <div class="brand">AIUB LostLink</div>
        <div class="nav-links">
          <a href="/WebTechnology-Project/Views/Post/details.php?id=<?= (int)$post['id'] ?>">Back</a>
          <a href="/WebTechnology-Project/Controllers/logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <div class="page">
    <div class="container">
      <div class="card">
        <h1>Report</h1>
        <p class="sub">Report this post or the post owner.</p>

        <?php if ($err === '1'): ?>
          <div class="err">Please select at least one reason.</div>
        <?php elseif ($err === 'db'): ?>
          <div class="err">Database error. Try again.</div>
        <?php endif; ?>

        <div class="box">
          <b>Post:</b> <?= htmlspecialchars($post['title']) ?><br>
          <b>Category:</b> <?= htmlspecialchars($post['category']) ?><br>
          <b>Location:</b> <?= htmlspecialchars($post['location']) ?>
        </div>

        <form method="post" action="/WebTechnology-Project/controllers/reportCheck.php">
          <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">

          <label>Report Target</label>
          <div class="row">
            <label><input type="radio" name="target" value="post" checked> Report this post</label>
            <label><input type="radio" name="target" value="user"> Report the post owner</label>
          </div>

          <label>Reasons</label>
          <div class="row">
            <label><input type="checkbox" name="reasons[]" value="Spam / Advertisement"> Spam / Advertisement</label>
            <label><input type="checkbox" name="reasons[]" value="Fake information"> Fake information</label>
            <label><input type="checkbox" name="reasons[]" value="Scam / Suspicious"> Scam / Suspicious</label>
            <label><input type="checkbox" name="reasons[]" value="Inappropriate content"> Inappropriate content</label>
            <label><input type="checkbox" name="reasons[]" value="Duplicate post"> Duplicate post</label>
            <label><input type="checkbox" name="reasons[]" value="Other"> Other</label>
          </div>

          <label>Extra Details (optional)</label>
          <textarea name="details" placeholder="Write anything specific (optional)"></textarea>

          <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn" type="submit" name="submit">Submit Report</button>
            <a class="btn secondary" href="/WebTechnology-Project/Views/Post/details.php?id=<?= (int)$post['id'] ?>">Cancel</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</body>
</html>
