<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Chat</title>

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
    .box{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px}

    .msgs{display:flex;flex-direction:column;gap:10px;max-height:420px;overflow:auto;padding:6px}
    .bubble{
      max-width:72%;
      padding:10px 12px;
      border-radius:14px;
      border:1px solid #e5e7eb;
      background:#fff;
    }
    .mine{align-self:flex-end;background:#eef2ff;border-color:#c7d2fe}
    .other{align-self:flex-start}
    .meta{font-size:12px;color:#666;margin-top:6px}

    form{margin-top:12px;display:flex;gap:10px}
    textarea{flex:1;resize:vertical;min-height:50px;padding:10px;border:1px solid #bbb;border-radius:10px}
    .btn{border:0;cursor:pointer;padding:10px 12px;border-radius:10px;background:#0f172a;color:#fff;font-size:14px}
    .err{margin-top:10px;color:#991b1b}
  </style>
</head>

<body>
  <div class="nav">
    <div class="container">
      <div class="nav-inner">
        <div class="brand">AIUB LostLink</div>
        <div class="nav-links">
          <a href="/WebTechnology-Project/Controllers/messagesCheck.php">Back</a>
          <a href="/WebTechnology-Project/Controllers/logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <div class="page">
    <div class="container">
      <div class="box">
        <h2 style="margin:0 0 10px;">
          Chat with <?php echo htmlspecialchars($otherUser ? $otherUser['username'] : 'User'); ?>
        </h2>

        <div class="msgs">
          <?php if (!isset($thread) || count($thread) === 0): ?>
            <div class="meta">No messages yet. Send the first message below.</div>
          <?php else: ?>
            <?php for ($i = 0; $i < count($thread); $i++): ?>
              <?php
                $m = $thread[$i];
                $isMine = ((int)$m['sender_id'] === (int)$userId);
                $cls = $isMine ? 'bubble mine' : 'bubble other';
                $text = isset($m['body']) ? $m['body'] : '';
                $time = isset($m['created_at']) ? $m['created_at'] : '';
              ?>
              <div class="<?php echo $cls; ?>">
                <div><?php echo htmlspecialchars($text); ?></div>
                <div class="meta"><?php echo htmlspecialchars($time); ?></div>
              </div>
            <?php endfor; ?>
          <?php endif; ?>
        </div>

        <?php $err = ''; if (isset($_GET['err'])) $err = $_GET['err']; ?>
        <?php if ($err === '1'): ?>
          <div class="err">Message cannot be empty.</div>
        <?php endif; ?>

        <form method="post" action="/WebTechnology-Project/Controllers/messageSendCheck.php">
          <input type="hidden" name="receiver_id" value="<?php echo (int)$otherId; ?>">
          <textarea name="body" placeholder="Type a message..."></textarea>
          <button class="btn" type="submit" name="send">Send</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>