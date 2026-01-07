<?php
require_once(__DIR__ . '/../../controllers/adminCheck.php');
require_once(__DIR__ . '/../../models/announcementModel.php');

$msg = $_GET['msg'] ?? '';
$activeRow = getActiveAnnouncement();
$latestRow = getLatestAnnouncement();

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Admin Announcement</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0;position:relative}
    .wrap{width:950px;max-width:94vw;margin:0 auto}
    h1{margin:0;font-size:20px}
    .sub{font-size:12px;color:#cbd5e1;margin-top:4px}
    .back{position:absolute;top:18px;right:24px;font-size:14px}
    .back a{color:#38bdf8;text-decoration:none}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 8px 22px rgba(15,23,42,.06);padding:16px;margin:18px 0}
    .msg{margin:12px 0;padding:10px 12px;border-radius:10px;border:1px solid #dbeafe;background:#eff6ff;color:#1e3a8a}
    .msg.bad{border-color:#fecaca;background:#fef2f2;color:#991b1b}
    label{display:block;margin:10px 0 6px;font-size:14px}
    input[type="text"]{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:10px}
    .row{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-top:10px}
    .btn{padding:10px 12px;border:0;border-radius:10px;background:#0ea5e9;color:#fff;cursor:pointer;font-weight:700}
    .btn.gray{background:#475569}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;border:1px solid #e2e8f0;background:#f8fafc}
    .badge.on{border-color:#a7f3d0;background:#ecfdf5;color:#065f46}
    .badge.off{border-color:#fecaca;background:#fef2f2;color:#991b1b}
    .small{font-size:12px;color:#64748b}
  </style>
</head>
<body>

<div class="top">
  <div class="wrap">
    <h1>Admin Global Announcement</h1>
    <div class="sub">Create an announcement banner shown on Post index page.</div>
  </div>
  <div class="back">
    Back to <a href="../Post/index.php">Home</a>
  </div>
</div>

<div class="wrap">

  <?php if ($msg): ?>
    <?php
      $isBad = in_array($msg, ['failed','empty'], true);
      $text = $msg;
      if ($msg === 'saved') $text = 'Announcement saved successfully.';
      if ($msg === 'empty') $text = 'Announcement message cannot be empty.';
      if ($msg === 'failed') $text = 'Failed to save. Please try again.';
    ?>
    <div class="msg <?php echo $isBad ? 'bad' : ''; ?>"><?php echo esc($text); ?></div>
  <?php endif; ?>

  <div class="card">
    <div style="margin-bottom:8px;">
      <b>Current Status:</b>
      <?php if ($activeRow && !empty($activeRow['message'])): ?>
        <span class="badge on">ACTIVE</span>
        <div class="small" style="margin-top:6px;"><?php echo esc($activeRow['message']); ?></div>
      <?php else: ?>
        <span class="badge off">INACTIVE</span>
        <div class="small" style="margin-top:6px;">No active announcement.</div>
      <?php endif; ?>
    </div>

    <hr style="border:0;border-top:1px solid #eef2f7;margin:14px 0;">

    <form method="POST" action="../../controllers/adminAnnouncementCheck.php">
      <label>Announcement Message (max ~255 chars)</label>
      <input type="text" name="message" value="<?php echo esc($latestRow['message'] ?? ''); ?>" placeholder="e.g., LostLink maintenance tonight 10 PM" required>

      <div class="row">
        <label style="display:flex;align-items:center;gap:8px;margin:0;">
          <input type="checkbox" name="active" value="1" <?php echo ($activeRow ? 'checked' : ''); ?>>
          Make this announcement active
        </label>

        <button class="btn" type="submit" name="submit">Save</button>
      </div>

      <div class="small" style="margin-top:8px;">
        If active is checked, this will replace any previous active announcement.
      </div>
    </form>
  </div>

</div>

</body>
</html>
