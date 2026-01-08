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
  <link rel="stylesheet" href="../AdminAnnouncement/AdminAnnoucement.css" />
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
