<?php
require_once(__DIR__ . '/../../controllers/adminCheck.php');
require_once(__DIR__ . '/../../models/postModel.php');

$msg = $_GET['msg'] ?? null;
$posts = getAllPostsForReview();

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function badgeClass($status) {
  $s = strtolower(trim((string)$status));
  if ($s === 'approved') return 'approved';
  if ($s === 'rejected') return 'rejected';
  return 'pending';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Admin Post Review</title>
  <style>
    *{
        box-sizing:border-box
    }
    body{
        margin:0;
        font-family:Arial,Helvetica,sans-serif;
        background:#f4f6fb;color:#111;
    }
    .top{
        background:#0f172a;
        color:#fff;padding:14px 0;
        position:relative;
    }
    .wrap{
        width:1200px;
        max-width:95vw;
        margin:0 auto;
    }
    h1{
        margin:0;
        font-size:20px
    }
    .sub{
        font-size:12px;
        color:#cbd5e1;
        margin-top:4px}

    .back{
        position:absolute;
        top:18px;
        right:24px;
        font-size:14px
    }
    .back a{
        color:#38bdf8;
        text-decoration:none
    }
    .back a:hover{
        text-decoration:underline
    }

    .card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        box-shadow:0 8px 22px rgba(15,23,42,.06);
        padding:16px;margin:18px 0;
    }
    .msg{
        margin:12px 0;
        padding:10px 12px;
        border-radius:10px;
        border:1px solid #dbeafe;
        background:#eff6ff;
        color:#1e3a8a
    }
    .msg.bad{
        border-color:#fecaca;
        background:#fef2f2;
        color:#991b1b
    }

    table{
        width:100%;
        border-collapse:collapse
    }
    th,td{
        padding:10px 12px;
        border-bottom:1px solid #eef2f7;
        text-align:left;
        vertical-align:top;
    }

    th{
        font-size:13px;
        color:#334155;
        background:#f8fafc;
        white-space:nowrap;
    }
    td{
        font-size:14px;
    }

    .badge{
        display:inline-block;
        padding:4px 10px;
        border-radius:999px;
        font-size:12px;
        border:1px solid #e2e8f0;
        background:#f8fafc;
    }
    .badge.pending{
        border-color:#fde68a;
        background:#fffbeb;
        color:#92400e
    }
    .badge.approved{
        border-color:#a7f3d0;
        background:#ecfdf5;
        color:#065f46
    }
    .badge.rejected{
        border-color:#fecaca;
        background:#fef2f2;
        color:#991b1b
    }


    select{
        padding:8px 10px;
        border:1px solid #d1d5db;
        border-radius:10px;
        background:#fff
    }
    button{
        padding:8px 12px;
        border:0;
        border-radius:10px;
        background:#0ea5e9;
        color:#fff;
        cursor:pointer
    }
    button:disabled{
        opacity:.6;
        cursor:not-allowed;
    }
    .small{
        font-size:12px;
        color:#64748b
    }
    .row-actions{
        display:flex;
        gap:10px;
        align-items:center
    }
    .desc{
        max-width:340px;
    }
  </style>
</head>
<body>

<div class="top">
  <div class="wrap">
    <h1>Admin Post Review</h1>
    <div class="sub">View all posts and approve/reject pending ones.</div>
  </div>
  <div class="back">
    Back to <a href="../Post/index.php">Home</a>
  </div>
</div>

<div class="wrap">
  <div class="card">

    <!-- AJAX message box (must not wrap the table) -->
    <div id="ajaxMsg" class="msg" style="display:none;"></div>

    <?php if ($msg): ?>
      <?php
        $isBad = in_array($msg, ['failed','invalid'], true);
        $text = $msg;
        if ($msg === 'updated') $text = 'Post status updated successfully.';
        if ($msg === 'failed')  $text = 'Failed to update status. Please try again.';
        if ($msg === 'invalid') $text = 'Invalid request.';
      ?>
      <div class="msg <?php echo $isBad ? 'bad' : ''; ?>"><?php echo esc($text); ?></div>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Location</th>
          <th>Posted By</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Student ID</th>
          <th>Status</th>
          <th>Created</th>
          <th>Change Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

      <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $p): ?>
          <?php
            $status = $p['status'] ?? 'pending';
            $isPending = (strtolower(trim((string)$status)) === 'pending');
            $formId = 'f_post_' . (int)$p['id'];
          ?>
          <tr>
            <td><?php echo (int)$p['id']; ?></td>

            <td>
              <div><strong><?php echo esc($p['title'] ?? ''); ?></strong></div>
              <div class="small desc"><?php echo esc($p['description'] ?? ''); ?></div>
            </td>

            <td><?php echo esc($p['category'] ?? ''); ?></td>
            <td><?php echo esc($p['location'] ?? ''); ?></td>
            <td><?php echo esc($p['posted_by_username'] ?? ''); ?></td>
            <td><?php echo esc($p['posted_by_email'] ?? ''); ?></td>
            <td><?php echo esc($p['phone'] ?? ''); ?></td>
            <td><?php echo esc($p['student_id'] ?? ''); ?></td>

            <td>
              <span class="badge <?php echo esc(badgeClass($status)); ?>">
                <?php echo esc($status); ?>
              </span>
            </td>

            <td class="small"><?php echo esc($p['created_at'] ?? ''); ?></td>

            <td>
              <select name="status" form="<?php echo esc($formId); ?>" <?php echo $isPending ? '' : 'disabled'; ?>>
                <option value="pending"  <?php echo (strtolower($status)==='pending'?'selected':''); ?>>Pending</option>
                <option value="approved" <?php echo (strtolower($status)==='approved'?'selected':''); ?>>Approved</option>
                <option value="rejected" <?php echo (strtolower($status)==='rejected'?'selected':''); ?>>Rejected</option>
              </select>

              <?php if (!$isPending): ?>
                <div class="small">Only pending can change</div>
              <?php endif; ?>
            </td>

            <td>
              <form class="js-ajax-post" id="<?php echo esc($formId); ?>" method="POST" action="../../controllers/adminPostReviewCheck.php">
                <!-- IMPORTANT: submit must exist for server (and for non-AJAX consistency) -->
                <input type="hidden" name="submit" value="1" />
                <input type="hidden" name="post_id" value="<?php echo (int)($p['id'] ?? 0); ?>" />

                <div class="row-actions">
                  <button type="submit" <?php echo $isPending ? '' : 'disabled'; ?>>Update</button>
                </div>
              </form>
            </td>

          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="12">No posts found.</td></tr>
      <?php endif; ?>

      </tbody>
    </table>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const msgBox = document.getElementById('ajaxMsg');

  function showMsg(text, bad) {
    if (!msgBox) return;
    msgBox.style.display = 'block';
    msgBox.className = 'msg' + (bad ? ' bad' : '');
    msgBox.textContent = text;
    setTimeout(() => { msgBox.style.display = 'none'; }, 2500);
  }

  document.querySelectorAll('form.js-ajax-post').forEach(function (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();

      const btn = form.querySelector('button[type="submit"]');
      const row = form.closest('tr');
      const select = row ? row.querySelector('select[name="status"]') : null;
      const badge = row ? row.querySelector('.badge') : null;

      if (!select || !btn) return;

      btn.disabled = true;

      const fd = new FormData(form);
      fd.set('status', select.value);
      fd.append('ajax', '1');
      fd.append('submit', '1'); // IMPORTANT: ensure server doesn't return "invalid"

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          body: fd,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await res.json();
        if (!data.ok) throw new Error(data.msg || 'failed');

        const newStatus = data.newStatus || select.value;

        if (badge) {
          badge.textContent = newStatus;
          badge.classList.remove('pending', 'approved', 'rejected');
          badge.classList.add(newStatus);
        }

        select.disabled = true;
        btn.disabled = true;

        showMsg('Post updated successfully.', false);
      } catch (err) {
        btn.disabled = false;
        showMsg('Update failed: ' + err.message, true);
      }
    });
  });
});
</script>

</body>
</html>
