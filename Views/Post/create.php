<?php
require_once('../../controllers/sessionCheck.php');
$err = $_GET['err'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Create Post</title>

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

    form{width:520px; max-width:92vw;} /* a bit wider for post form */

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
      font-weight:700;
      color:#3a1695;
      padding:0;
      margin:0 0 14px;
      line-height:1.2;
    }

    .sub{
      clear:both;
      text-align:center;
      font-size:13px;
      color:#555;
      margin:0 0 14px;
    }

    table{width:100%; border-collapse:collapse; clear:both;}
    td{padding:8px 0; vertical-align:middle;}

    .line{
      border-bottom:1px solid rgba(0,0,0,.35);
    }

    .ico{
      width:30px;
      padding-right:10px;
      opacity:.65;
    }
    .ico svg{display:block; width:18px; height:18px; fill:#1a1a1a;}

    input[type="text"], select, textarea{
      width:100%;
      border:0;
      outline:none;
      background:transparent;
      padding:10px 0;
      font-size:14px;
      color:#111;
      font-family: Arial, Helvetica, sans-serif;
    }

    input::placeholder, textarea::placeholder{
      color:rgba(0,0,0,.45);
    }

    textarea{
      resize:vertical;
      min-height:110px;
      line-height:1.35;
    }

    tr.line:focus-within{
      border-bottom-color:#6b2cff;
      box-shadow: 0 10px 18px -16px rgba(107,44,255,.75);
    }

    /* dropdown look */
    select{
      appearance:none;
      -webkit-appearance:none;
      -moz-appearance:none;
      cursor:pointer;
    }

    .hintRow{
      padding-top:10px;
      font-size:12px;
      color:#555;
    }

    .navLinks{
      display:flex;
      gap:10px;
      justify-content:space-between;
      flex-wrap:wrap;
      align-items:center;
    }

    a{color:#6b2cff; text-decoration:none;}
    a:hover{text-decoration:underline;}

    .btn{
      width:100%;
      padding:11px 12px;
      border:0;
      border-radius:8px;
      cursor:pointer;
      font-weight:700;
      letter-spacing:.2px;
      color:#fff;
      background: linear-gradient(90deg, #1b103a 0%, #0e0a1e 100%);
      box-shadow: 0 12px 18px rgba(0,0,0,.18);
    }
    .btn:hover{filter:brightness(1.08);}

    .err{
      clear:both;
      padding:10px 12px;
      border-radius:10px;
      background:#fee2e2;
      border:1px solid #fecaca;
      color:#991b1b;
      margin:0 0 12px;
      font-size:13px;
    }

    @media (max-width:520px){
      fieldset{padding:28px 22px 22px}
      legend{font-size:28px}
    }
  </style>
</head>

<body>

  <form method="post" action="../../controllers/postCreateCheck.php">
    <fieldset>
      <legend>Create Post</legend>
      <p class="sub">Submit your Lost/Found post with proper details.</p>

      <?php if ($err === '1'): ?>
        <div class="err">Please fill all fields correctly.</div>
      <?php elseif ($err === 'db'): ?>
        <div class="err">Database error while saving post.</div>
      <?php endif; ?>

      <table>
        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/>
            </svg>
          </td>
          <td>
            <select name="category" required>
              <option value="Lost">Lost</option>
              <option value="Found">Found</option>
            </select>
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4 4h16v2H4V4zm0 5h16v2H4V9zm0 5h10v2H4v-2z"/>
            </svg>
          </td>
          <td>
            <input type="text" name="title" required placeholder="Title (e.g., Lost Wallet - Black)">
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/>
            </svg>
          </td>
          <td>
            <input type="text" name="location" required placeholder="Location (e.g., Campus A - Library)">
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M6.6 10.8c1.4 2.7 3.9 5.2 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.2 1 .4 2.1.6 3.2.6.7 0 1.3.6 1.3 1.3V21c0 .7-.6 1.3-1.3 1.3C10.4 22.3 1.7 13.6 1.7 3.3 1.7 2.6 2.3 2 3 2h3.5c.7 0 1.3.6 1.3 1.3 0 1.1.2 2.2.6 3.2.1.4 0 .9-.2 1.2l-2.6 3.1z"/>
            </svg>
          </td>
          <td>
            <input type="text" name="phone" required placeholder="Phone (e.g., 01711-111111)">
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4 4h16v16H4V4zm2 2v12h12V6H6zm2 2h8v2H8V8zm0 4h8v2H8v-2z"/>
            </svg>
          </td>
          <td>
            <input type="text" name="student_id" required placeholder="Student ID (e.g., 22-12345-1)">
          </td>
        </tr>

        <tr class="line">
          <td class="ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4 4h16v2H4V4zm0 4h16v14H4V8zm2 2v10h12V10H6z"/>
            </svg>
          </td>
          <td>
            <textarea name="description" required placeholder="Write details that help identify the item..."></textarea>
          </td>
        </tr>

        <tr class="hintRow">
          <td colspan="2">
            <div class="navLinks">
              <div>
                <a href="index.php">← Back to Posts</a> |
                <a href="index.php?category=Lost">Lost</a> |
                <a href="index.php?category=Found">Found</a>
              </div>
              <div>
                <a href="../../controllers/logout.php">Logout</a>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <input class="btn" type="submit" name="submit" value="Post">
          </td>
        </tr>
      </table>

    </fieldset>
  </form>

</body>
</html>
