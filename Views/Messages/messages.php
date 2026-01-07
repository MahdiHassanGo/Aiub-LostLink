<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Messages</title>

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
    h1{margin:0 0 6px;font-size:22px}
    .sub{margin:0;color:#444;font-size:14px}

    .list{margin-top:16px}
    .chat{
      background:#fff;border:1px solid #e5e7eb;border-radius:14px;
      padding:12px 14px;margin-bottom:10px;
      display:flex;justify-content:space-between;gap:12px;
      text-decoration:none;color:#111;
    }
    .chat:hover{border-color:#cbd5e1}
    .name{margin:0 0 6px;font-weight:700}
    .last{margin:0;color:#444;font-size:14px;line-height:1.35}
    .meta{font-size:12px;color:#666;margin-top:8px}
    .badge{
      display:inline-block;
      font-size:12px;
      padding:4px 8px;
      border-radius:999px;
      background:#0f172a;color:#fff;
      height:fit-content;
    }
    .empty{
      background:#fff;border:1px solid #e5e7eb;border-radius:14px;
      padding:18px;color:#444;margin-top:16px;
    }
  </style>
</head>

<body>
  <div class="nav">
    <div class="container">
      <div class="nav-inner">
        <div class="brand">AIUB LostLink</div>
        <div class="nav-links">
          <a href="/WebTechnology-Project/Views/HomePage/homepage.php">Home</a>
          <a href="/WebTechnology-Project/Controllers/logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <div class="page">
    <div class="container">
      <h1>Messages</h1>
      <p class="sub">All your chats will show up here.</p>

      <div class="list">
        <?php if (!isset($chats) || count($chats) === 0): ?>
          <div class="empty">No chats yet. When you send a claim message under a post, the chat will appear here.</div>
        <?php else: ?>
          <?php for ($i = 0; $i < count($chats); $i++): ?>
            <?php
              $c = $chats[$i];
              $name = isset($c['name']) ? $c['name'] : 'User';
              $last = isset($c['last_message']) ? $c['last_message'] : '';
              $time = isset($c['created_at']) ? $c['created_at'] : '';
              $link = isset($c['link']) ? $c['link'] : '/WebTechnology-Project/Controllers/messagesCheck.php';
              $unread = (isset($c['is_read']) && (int)$c['is_read'] === 0);
            ?>
            <a class="chat" href="<?php echo $link; ?>">
              <div>
                <p class="name"><?php echo htmlspecialchars($name); ?></p>
                <p class="last"><?php echo htmlspecialchars($last); ?></p>
                <div class="meta"><?php echo htmlspecialchars($time); ?></div>
              </div>
              <?php if ($unread): ?>
                <div class="badge">New</div>
              <?php endif; ?>
            </a>
          <?php endfor; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
