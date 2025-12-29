<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Login</title>
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
    .note{font-size:12px;color:#666;margin-top:10px;line-height:1.4}
  </style>
</head>
<body>
  <div class="container">
    <h2>User Login</h2>

    <form method="post" action="../../controllers/loginCheck.php">
      <label>Email</label>
      <input type="email" name="email" required placeholder="name@aiub.edu">

      <label>Password</label>
      <input type="password" name="password" required placeholder="Your password">

      <input class="btn" type="submit" name="submit" value="Login">

      <div class="help">
        Don’t have an account? <a href="signup.php">Register here</a>
      </div>

    </form>
  </div>
</body>
</html>

