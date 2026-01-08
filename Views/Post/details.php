<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../../controllers/sessionCheck.php');
require_once('../../models/postModel.php');
require_once('../../models/commentModel.php');

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$id = (int)($_GET['id'] ?? 0);
$post = ($id > 0) ? getPostById($id) : false;

$suggestions = false;
if ($post) {
  $suggestions = getSmartSuggestions(
    $post['id'],
    $post['title'],
    $post['location'],
    $post['category']
  );
}

$msg = $_GET['msg'] ?? '';
$err = $_GET['err'] ?? '';

$comments = [];
if ($post) {
  $comments = getCommentsByPostId($post['id']);
}
$c_msg = $_GET['c_msg'] ?? '';
$c_err = $_GET['c_err'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Post Details</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0}
    .wrap{width:1050px;max-width:94vw;margin:0 auto}
    .top-inner{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .links a{color:#93c5fd;text-decoration:none;margin-left:10px}
    .links a:hover{text-decoration:underline}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:18px 0}
    .tag{display:inline-block;font-size:12px;padding:4px 9px;border-radius:999px;background:#eef2ff;margin-bottom:10px}
    .msg{background:#dcfce7;border:1px solid #86efac;color:#166534;padding:10px;border-radius:10px;margin-bottom:12px}
    .err{background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:10px;border-radius:10px;margin-bottom:12px}
    label{display:block;margin:10px 0 6px;font-size:14px}
    input,textarea{width:100%;padding:10px;border:1px solid #bbb;border-radius:10px}
    textarea{min-height:90px;resize:vertical}
    .btn{display:inline-block;padding:10px 12px;border-radius:10px;background:#2c7be5;color:#fff;text-decoration:none;font-size:14px;font-weight:800;border:0;cursor:pointer}
    .btn:hover{background:#1a5fd0}
    .empty{background:#fff;border:1px dashed #bbb;padding:16px;border-radius:12px}
    .post-card{position:relative}
    .report-btn{position:absolute;top:16px;right:16px;padding:8px 10px;font-size:13px}
    .small{font-size:12px;color:#64748b}
  </style>
</head>
<body>
  <div class="top">
    <div class="wrap top-inner">
      <div><b>AIUB LostLink</b> • Details</div>
      <div class="links">
        <a href="index.php">All</a>
        <a href="index.php?category=Lost">Lost</a>
        <a href="index.php?category=Found">Found</a>
        <a href="create.php">+ Create</a>
        <a href="./index.php">Back</a>
        <a href="../../controllers/logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="wrap">
    <?php if (!$post): ?>
      <div class="empty">Post not found.</div>
    <?php else: ?>

      <div class="card post-card">
        <a class="btn report-btn" href="/WebTechnology-Project/controllers/reportCheck.php?post_id=<?= (int)$post['id'] ?>">Report</a>

        <div class="tag"><?= esc($post['category']) ?></div>
        <h2 style="margin:6px 0 10px;"><?= esc($post['title']) ?></h2>

        <p style="margin:0 0 10px;line-height:1.6;">
          <b>Location:</b> <?= esc($post['location']) ?><br>
          <b>Phone (poster):</b> <?= esc($post['phone']) ?><br>
          <b>Student ID:</b> <?= esc($post['student_id']) ?><br>
          <b>Posted:</b> <?= esc($post['created_at']) ?><br>
        </p>

        <p style="margin:0;line-height:1.6;">
          <b>Description:</b><br>
          <?= nl2br(esc($post['description'])) ?>
        </p>
      </div>

      <!-- CLAIM (AJAX + JS validation) -->
      <div class="card">
        <h3 style="margin:0 0 10px;">Claim this item</h3>

        <!-- Non-AJAX fallback messages -->
        <?php if ($msg === 'claim_sent'): ?>
          <div class="msg">Claim request sent ✅</div>
        <?php endif; ?>
        <?php if ($err === '1'): ?>
          <div class="err">Please fill your name and phone correctly.</div>
        <?php elseif ($err === 'db'): ?>
          <div class="err">Database error while sending claim.</div>
        <?php endif; ?>

        <!-- AJAX message boxes -->
        <div id="claimOk" class="msg" style="display:none;"></div>
        <div id="claimErr" class="err" style="display:none;"></div>

        <form id="claimForm" method="post" action="../../controllers/claimCheck.php">
          <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">

          <label>Your Name</label>
          <input id="claimantName" type="text" name="claimant_name" required minlength="2" placeholder="e.g., Rahim">

          <label>Your Phone</label>
          <input id="claimantPhone" type="tel" name="claimant_phone" required placeholder="e.g., 017XXXXXXXX">

          <label>Message (optional)</label>
          <textarea id="claimantMsg" name="message" maxlength="500"
            placeholder="Describe proof (color, sticker, anything only owner knows)"></textarea>

          <button id="claimBtn" class="btn" type="submit" name="submit">Send Claim Request</button>
          <div class="small" style="margin-top:8px;">Tip: use a phone like 017XXXXXXXX.</div>
        </form>
      </div>

      <script>
      document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('claimForm');
        if (!form) return;

        const okBox  = document.getElementById('claimOk');
        const errBox = document.getElementById('claimErr');
        const btn    = document.getElementById('claimBtn');

        const nameEl  = document.getElementById('claimantName');
        const phoneEl = document.getElementById('claimantPhone');
        const msgEl   = document.getElementById('claimantMsg');

        function showOk(text){
          okBox.style.display = 'block';
          okBox.textContent = text;
          errBox.style.display = 'none';
        }

        function showErr(text){
          errBox.style.display = 'block';
          errBox.textContent = text;
          okBox.style.display = 'none';
        }

        function normalizePhone(raw){
          let p = (raw || '').trim();
          p = p.replace(/[^\d+]/g, '');
          p = p.replace(/\+/g, '');
          if (p.startsWith('88')) p = p.slice(2);
          return p;
        }

        function validBDPhone(p){
          return /^01[3-9]\d{8}$/.test(p);
        }

        form.addEventListener('submit', async function (e) {
          e.preventDefault();

          const name = (nameEl.value || '').trim();
          const phoneNorm = normalizePhone(phoneEl.value);
          const msg = (msgEl.value || '').trim();

          if (name.length < 2) return showErr('Name must be at least 2 characters.');
          if (!validBDPhone(phoneNorm)) return showErr('Phone must be a valid BD number like 017XXXXXXXX.');
          if (msg.length > 500) return showErr('Message must be within 500 characters.');

          btn.disabled = true;

          const fd = new FormData(form);
          fd.set('claimant_name', name);
          fd.set('claimant_phone', phoneNorm);
          fd.set('message', msg);
          fd.append('ajax', '1');
          fd.append('submit', '1');

          try {
            const res = await fetch(form.action, {
              method: 'POST',
              body: fd,
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await res.json();
            if (!data.ok) throw new Error(data.msgHuman || data.msg || 'failed');

            showOk('Claim request sent ✅');
            msgEl.value = '';
          } catch (err) {
            showErr('Update failed: ' + err.message);
          } finally {
            btn.disabled = false;
          }
        });
      });
      </script>

      <!-- COMMENTS (kept as your normal POST) -->
      <div class="card">
        <h3 style="margin:0 0 10px;">Comments</h3>

        <?php if ($c_msg === '1'): ?>
          <div class="msg">Comment posted ✅</div>
        <?php endif; ?>

        <?php if ($c_err === '1'): ?>
          <div class="err">Comment cannot be empty.</div>
        <?php endif; ?>

        <form method="post" action="../../controllers/commentCheck.php">
          <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">

          <label>Write a comment</label>
          <textarea name="comment" required placeholder="Write your comment..."></textarea>

          <button class="btn" type="submit" name="submit">Post Comment</button>
        </form>

        <div style="margin-top:14px;">
          <?php if (!isset($comments) || count($comments) === 0): ?>
            <div class="empty">No comments yet.</div>
          <?php else: ?>
            <?php foreach ($comments as $c): ?>
              <div style="border:1px solid #e5e7eb;border-radius:12px;padding:12px;margin-bottom:10px;background:#fff;">
                <div style="font-size:13px;color:#444;margin-bottom:6px;">
                  <b><?= esc($c['username']) ?></b> • <?= esc($c['created_at']) ?>
                </div>
                <div style="white-space:pre-wrap;line-height:1.5;"><?= esc($c['comment']) ?></div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- SIMILAR POSTS -->
      <?php if ($suggestions && mysqli_num_rows($suggestions) > 0): ?>
        <div class="card">
          <h3 style="margin:0 0 12px;">🔍 Similar <?= esc($post['category']) ?> Items</h3>

          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;">
            <?php while ($s = mysqli_fetch_assoc($suggestions)): ?>
              <div style="border:1px solid #e5e7eb;border-radius:10px;padding:12px;">
                <div class="tag"><?= esc($s['category']) ?></div>
                <h4 style="margin:6px 0 6px;font-size:15px;"><?= esc($s['title']) ?></h4>
                <p style="margin:0 0 8px;font-size:13px;color:#444;"><?= esc($s['location']) ?></p>
                <a class="btn" href="details.php?id=<?= (int)$s['id'] ?>" style="font-size:12px;">View</a>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      <?php endif; ?>

    <?php endif; ?>
  </div>
</body>
</html>
