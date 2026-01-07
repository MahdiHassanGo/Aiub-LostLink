<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Notifications</title>

  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .nav{background:#0f172a;padding:14px 0;color:#fff}
    .container{width:1050px;max-width:1050px;margin:0 auto;padding:0 16px}
    .nav-inner{display:flex;align-items:center;justify-content:space-between}
    .brand{font-weight:700;letter-spacing:.3px}
    .nav-links a{
      color:#fff;text-decoration:none;display:inline-block;margin-left:10px;
      padding:8px 12px;border-radius:10px;background:rgba(255,255,255,.10)
    }
    .nav-links a:hover{background:rgba(255,255,255,.16)}
    .page{padding:22px 0}
    .header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap}
    h1{margin:0 0 6px;font-size:22px}
    .sub{margin:0;color:#444;font-size:14px}
    .actions{display:flex;gap:10px;flex-wrap:wrap}
    .btn{
      border:0;cursor:pointer;
      padding:10px 12px;border-radius:10px;
      background:#0f172a;color:#fff;
      font-size:14px;
    }
    .btn.secondary{background:#e5e7eb;color:#111}
    .list{margin-top:16px}
    .note{
      background:#fff;border:1px solid #e5e7eb;border-radius:14px;
      padding:14px 14px;margin-bottom:10px;
      display:flex;justify-content:space-between;gap:12px;
    }
    .note.unread{border-left:6px solid #0f172a}
    .note-title{margin:0 0 6px;font-size:15px}
    .note-body{margin:0 0 8px;color:#444;font-size:14px;line-height:1.35}
    .note-meta{font-size:12px;color:#666}
    .note-right{display:flex;flex-direction:column;align-items:flex-end;gap:10px;min-width:140px}
    .small-btn{
      border:0;cursor:pointer;
      padding:8px 10px;border-radius:10px;
      background:#0f172a;color:#fff;font-size:13px;
    }
    .small-btn.secondary{background:#e5e7eb;color:#111;text-decoration:none;display:inline-block}
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
          <a href="/WebTechnology-Project/Controllers/logout.php">Logout</a>
          <a href="/WebTechnology-Project/Views/HomePage/homepage.php">Home</a>
        </div>
      </div>
    </div>
  </div>

  <div class="page">
    <div class="container">
      <div class="header">
        <div>
          <h1>Notifications</h1>
          <p class="sub">Unread notifications are highlighted.</p>
        </div>

        <div class="actions">
          <form method="post" action="/WebTechnology-Project/Controllers/notificationsCheck.php" style="display:inline;">
            <input type="hidden" name="action" value="mark_all_read">
            <button class="btn" type="submit">Mark all as read</button>
          </form>

          <form method="post" action="/WebTechnology-Project/Controllers/notificationsCheck.php" style="display:inline;">
            <input type="hidden" name="action" value="clear_all">
            <button class="btn secondary" type="submit">Clear all</button>
          </form>
        </div>
      </div>

      <div class="list">
        <?php if (!isset($notifications) || count($notifications) === 0): ?>
          <div class="empty">No notifications yet.</div>
        <?php else: ?>
          <?php foreach ($notifications as $n): ?>
            <?php
              $isUnread = ((int)($n['is_read'] ?? 0) === 0);
              $boxClass = $isUnread ? 'note unread' : 'note';

              $title = htmlspecialchars($n['title'] ?? '', ENT_QUOTES, 'UTF-8');
              $body  = htmlspecialchars((string)($n['body'] ?? ''), ENT_QUOTES, 'UTF-8');
              $type  = htmlspecialchars($n['type'] ?? '', ENT_QUOTES, 'UTF-8');

              $timeText = '';
              if (!empty($n['created_at'])) {
                $timeText = date("M d, Y h:i A", strtotime($n['created_at']));
              }

              $link = trim((string)($n['link'] ?? ''));
            ?>

            <div class="<?= $boxClass ?>">
              <div>
                <p class="note-title"><b><?= $title ?></b></p>

                <?php if ($body !== ''): ?>
                  <p class="note-body"><?= $body ?></p>
                <?php endif; ?>

                <div class="note-meta">Type: <?= $type ?> • <?= htmlspecialchars($timeText, ENT_QUOTES, 'UTF-8') ?></div>
              </div>

              <div class="note-right">
                <?php if ($isUnread): ?>
                  <form method="post" action="/WebTechnology-Project/Controllers/notificationsCheck.php" style="display:inline;">
                    <input type="hidden" name="action" value="mark_read">
                    <input type="hidden" name="id" value="<?= (int)($n['id'] ?? 0) ?>">
                    <button class="small-btn" type="submit">Mark read</button>
                  </form>
                <?php endif; ?>

                <?php if ($link !== ''): ?>
                  <a class="small-btn secondary" href="<?= htmlspecialchars($link, ENT_QUOTES, 'UTF-8') ?>">Open</a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </div>
</body>
</html>
