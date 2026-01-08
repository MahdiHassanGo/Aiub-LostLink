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
 <link rel="stylesheet" href="../AdminPostReview/AdminPostReview.css" />
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
