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
      align-items:flex-start;
      justify-content:center;
      padding:36px 0;
      background: linear-gradient(135deg, #5d22ff 0%, #3c0fb0 45%, #240064 100%);
      color:#111;
    }

    /* container */
    .wrap{
      width:820px;
      max-width:92vw;
    }

    /* top bar (styled like a small header chip) */
    .top{
      background: rgba(255,255,255,.12);
      border: 1px solid rgba(255,255,255,.22);
      color:#fff;
      padding:12px 14px;
      border-radius:12px;
      box-shadow: 0 12px 28px rgba(0,0,0,.22);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
    }

    .top-inner{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      flex-wrap:wrap;
    }

    .brand{
      font-weight:800;
      letter-spacing:.2px;
      display:flex;
      gap:8px;
      align-items:center;
      font-size:15px;
    }

    .links{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      font-size:13px;
    }

    a{color:#e9d5ff; text-decoration:none;}
    a:hover{text-decoration:underline}

    /* main card like login/signup */
    .card{
      margin-top:18px;
      background:#ffffff;
      border-radius:12px;
      box-shadow: 0 18px 45px rgba(0,0,0,.28);
      padding:26px 28px 22px;
    }

    .title{
      font-size:26px;
      font-weight:900;
      color:#3a1695;
      text-align:center;
      margin:0 0 6px;
    }

    .sub{
      text-align:center;
      font-size:13px;
      color:#666;
      margin:0 0 18px;
    }

    .err{
      margin:0 0 14px;
      padding:10px 12px;
      border-radius:10px;
      background:#fee2e2;
      border:1px solid #fecaca;
      color:#991b1b;
      font-size:13px;
    }

    label{
      display:block;
      margin:12px 0 6px;
      font-size:13px;
      color:#333;
      font-weight:700;
    }

    input, select, textarea{
      width:100%;
      padding:11px 12px;
      border:1px solid rgba(0,0,0,.25);
      border-radius:10px;
      outline:none;
      font-size:14px;
      background:#fff;
    }

    textarea{min-height:130px; resize:vertical;}

    input:focus, select:focus, textarea:focus{
      border-color:#6b2cff;
      box-shadow: 0 10px 18px -16px rgba(107,44,255,.75);
    }

    .grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:12px;
    }

    .btn{
      width:100%;
      margin-top:16px;
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

    @media (max-width:700px){
      body{padding:22px 0}
      .card{padding:22px 20px}
      .grid{grid-template-columns:1fr}
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="top">
      <div class="top-inner">
        <div class="brand">AIUB LostLink <span style="opacity:.8;">•</span> Create Post</div>
        <div class="links">
          <a href="index.php">All Posts</a> |
          <a href="index.php?category=Lost">Lost</a> |
          <a href="index.php?category=Found">Found</a> |
          <a href="../../controllers/logout.php">Logout</a>
        </div>
      </div>
    </div>

    <div class="card">
      <h1 class="title">Create Post</h1>
      <p class="sub">Share details so others can help find or claim the item.</p>

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

        <div class="grid">
          <div>
            <label>Phone</label>
            <input type="text" name="phone" required placeholder="e.g., 01711-111111">
          </div>

          <div>
            <label>Student ID</label>
            <input type="text" name="student_id" required placeholder="e.g., 22-12345-1">
          </div>
        </div>

    </fieldset>
  </form>

</body>
</html>
