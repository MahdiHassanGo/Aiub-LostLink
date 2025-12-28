<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Signup</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6f8;color:#111}
    .container{width:420px;max-width:92vw;margin:90px auto;background:#fff;padding:24px;border:1px solid #d9d9d9;border-radius:10px}
    h2{margin:0 0 18px;text-align:center;color:#222}
    label{display:block;margin:12px 0 6px;font-size:14px;color:#333}
    input{width:100%;padding:10px;border:1px solid #bbb;border-radius:8px}
    .btn{width:100%;margin-top:14px;padding:10px 12px;background:#2c7be5;border:0;color:#fff;border-radius:8px;cursor:pointer;font-weight:600}
    .btn:hover{background:#1a5fd0}
    .help{margin-top:14px;text-align:center;font-size:14px}
    a{color:#2c7be5;text-decoration:none}
    a:hover{text-decoration:underline}
  </style>
</head>
<body>
  <div class="container">
    <h2>User Registration</h2>

    <form method="post" action="../../controllers/signupCheck.php" enctype="application/x-www-form-urlencoded">
      <label>Username</label>
      <input type="text" name="username" required placeholder="Your name">

      <label>Email</label>
      <input type="email" name="email" required placeholder="name@aiub.edu">

      <label>Password</label>
      <input type="password" name="password" required placeholder="Create a password">

      <input class="btn" type="submit" name="submit" value="Register">

      <div class="help">
        Already have an account? <a href="login.php">Back to login</a>
      </div>
    </form>
  </div>
</body>
</html>
