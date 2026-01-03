<?php

require_once('../sessionCheck.php');
require_once('../../models/claimModel.php');

$userId = $_SESSION['user']['id'];
$claims = getClaimsByUser($userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Claim Requests</title>
  <style>
    body{margin:0;font-family:Arial;background:#f4f6fb}
    .wrap{width:1050px;max-width:94vw;margin:20px auto}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:14px}
    .tag{display:inline-block;font-size:12px;padding:4px 9px;border-radius:999px;background:#eef2ff}
    .status{font-weight:bold}
    .Pending{color:#ca8a04}
    .Approved{color:#15803d}
    .Rejected{color:#b91c1c}
    .btn{display:inline-block;padding:6px 10px;border-radius:8px;background:#2c7be5;color:#fff;text-decoration:none;font-size:12px}
  </style>
</head>
<body>

<div class="wrap">
  <h2>📄 My Claim Requests</h2>

  <?php if (!$claims || mysqli_num_rows($claims) === 0): ?>
    <div class="card">You have not submitted any claim requests yet.</div>
  <?php else: ?>
    <?php while ($row = mysqli_fetch_assoc($claims)): ?>
      <div class="card">
        <div class="tag"><?= htmlspecialchars($row['category']) ?></div>

        <h3 style="margin:6px 0">
          <?= htmlspecialchars($row['title']) ?>
        </h3>

        <p style="margin:0 0 8px;color:#444">
          <b>Location:</b> <?= htmlspecialchars($row['location']) ?><br>
          <b>Requested on:</b> <?= htmlspecialchars($row['created_at']) ?>
        </p>

        <p class="status <?= $row['status'] ?>">
          Status: <?= $row['status'] ?>
        </p>

        <a class="btn" href="../Post/details.php?id=<?= (int)$row['post_id'] ?>">
          View Post
        </a>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>

</body>
</html>
