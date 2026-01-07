<?php
session_start();
$msg = $_GET['msg'] ?? '';
$err = $_GET['err'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Forgot Password</title>
  <style>
    body{margin:0;font-family:Arial,Helvetica,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f4f6fb}
    .box{width:420px;max-width:92vw;background:#fff;padding:22px;border:1px solid #ddd;border-radius:12px}
    h2{margin:0 0 14px}
    label{display:block;margin:12px 0 6px;font-size:14px}
    input{width:100%;padding:10px;border:1px solid #bbb;border-radius:8px}
    .btn{margin-top:14px;width:100%;padding:10px;border:0;border-radius:8px;background:#2c7be5;color:#fff;font-weight:700;cursor:pointer}
    .msg{margin:10px 0;color:green}
    .err{margin:10px 0;color:#c00}
    a{text-decoration:none}
  </style>
</head>
<body>
  <div class="box">
    <h2>Reset Password</h2>

    <?php if($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if($err): ?><div class="err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

    <form method="post" action="../../Controllers/forgotPasswordCheck.php">
      <label>Email</label>
      <input type="email" name="email" required placeholder="name@aiub.edu">

      <label>New Password</label>
      <input type="password" name="new_password" required>

      <label>Confirm Password</label>
      <input type="password" name="confirm_password" required>

      <button class="btn" type="submit" name="submit">Update Password</button>
    </form>

    <p style="margin-top:12px;">
      <a href="login.php">← Back to Login</a>
    </p>
  </div>
</body>
</html>
