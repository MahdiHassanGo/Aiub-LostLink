<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Signup</title>

  <style>
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: Arial, Helvetica, sans-serif;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, #5d22ff 0%, #3c0fb0 45%, #240064 100%);
    }

    form{width:420px; max-width:92vw;}

    fieldset{
      border:0;
      background:#ffffff;
      padding:32px 34px 26px;
      border-radius:12px;
      box-shadow: 0 18px 45px rgba(0,0,0,.28);
    }

    legend{
      float:left;
      width:100%;
      text-align:center;
      font-size:30px;
      font-weight:800;
      color:#3a1695;
      padding:0;
      margin:0 0 12px;
      line-height:1.2;
    }

    .sub{
      clear:both;
      text-align:center;
      font-size:13px;
      color:#5a5a5a;
      margin-bottom:12px;
    }

    table{width:100%; border-collapse:collapse; clear:both;}
    td{padding:8px 0; vertical-align:middle;}

    .line{border-bottom:1px solid rgba(0,0,0,.35);}

    .ico{
      width:30px;
      padding-right:10px;
      opacity:.65;
    }
    .ico svg{display:block; width:18px; height:18px; fill:#1a1a1a;}

    input[type="text"], input[type="email"], input[type="password"]{
      width:100%;
      border:0;
      outline:none;
      background:transparent;
      padding:10px 0;
      font-size:14px;
      color:#111;
    }

    input::placeholder{color:rgba(0,0,0,.45);}

    tr.line:focus-within{
      border-bottom-color:#6b2cff;
      box-shadow: 0 10px 18px -16px rgba(107,44,255,.75);
    }

    .btn{
      width:100%;
      margin-top:10px;
      padding:11px 12px;
      border:0;
      border-radius:8px;
      cursor:pointer;
      font-weight:800;
      letter-spacing:.2px;
      color:#fff;
      background: linear-gradient(90deg, #1b103a 0%, #0e0a1e 100%);
      box-shadow: 0 12px 18px rgba(0,0,0,.18);
    }
    .btn:hover{filter:brightness(1.08);}

    .help{
      text-align:center;
      font-size:13px;
      color:#444;
      padding-top:14px;
    }

    a{color:#6b2cff; text-decoration:none;}
    a:hover{text-decoration:underline;}

    .err{
      clear:both;
      display:none;
      margin:0 0 12px;
      padding:10px 12px;
      border-radius:10px;
      background:#fee2e2;
      border:1px solid #fecaca;
      color:#991b1b;
      font-size:13px;
    }

    .hint{
      font-size:12px;
      color:#666;
      margin-top:6px;
      line-height:1.35;
    }

    @media (max-width:420px){
      fieldset{padding:28px 22px 22px}
      legend{font-size:28px}
    }
  </style>
</head>

<body>

  <!-- ✅ major code unchanged: method, action, existing names -->
  <form id="signupForm" method="post" action="../../Controllers/signupCheck.php">
    <fieldset>
      <legend>Sign up</legend>
      <div class="sub">Create your account to post Lost & Found items.</div>

      <div id="errBox" class="err"></div>

      <table>
        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.51 4.51 0 0 0 12 12Zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5Z"/>
            </svg>
          </td>
          <td>
            <input type="text" name="username" required placeholder="Your name">
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5L4 8V6l8 5 8-5Z"/>
            </svg>
          </td>
          <td>
            <input type="email" name="email" required placeholder="name@aiub.edu">
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 0 1 4 0v2h-4Z"/>
            </svg>
          </td>
          <td>
            <input
              type="password"
              name="password"
              id="password"
              required
              placeholder="Create a password"
              minlength="8"
              pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$"
              title="Password must be at least 8 characters and include 1 letter, 1 number, and 1 special character."
            >
            <div class="hint">
              Must be 8+ chars and include <b>letter</b>, <b>number</b> & <b>special</b> character.
            </div>
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 0 1 4 0v2h-4Z"/>
            </svg>
          </td>
          <td>
            <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm password">
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <input class="btn" type="submit" name="submit" value="Register">
          </td>
        </tr>

        <tr>
          <td colspan="2" class="help">
            Already have an account? <a href="login.php">Back to login</a>
          </td>
        </tr>
      </table>

    </fieldset>
  </form>

  <script>
    const form = document.getElementById('signupForm');
    const pw = document.getElementById('password');
    const cpw = document.getElementById('confirm_password');
    const errBox = document.getElementById('errBox');

    function showErr(msg){
      errBox.textContent = msg;
      errBox.style.display = 'block';
    }
    function clearErr(){
      errBox.textContent = '';
      errBox.style.display = 'none';
    }

    form.addEventListener('submit', function(e){
      clearErr();

      const password = pw.value;
      const strong = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

      if (!strong.test(password)) {
        e.preventDefault();
        showErr('Password must be 8+ characters and include at least 1 letter, 1 number, and 1 special character.');
        pw.focus();
        return;
      }

      if (password !== cpw.value) {
        e.preventDefault();
        showErr('Confirm Password does not match.');
        cpw.focus();
        return;
      }
    });
  </script>

</body>
</html>
