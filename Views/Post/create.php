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
      color:#111;
      min-height:100vh;

      /* ✅ your previous theme */
      background: linear-gradient(135deg, #5d22ff 0%, #3c0fb0 45%, #240064 100%);
      position:relative;
      overflow-x:hidden;
    }

    /* ✅ subtle glow overlay (no HTML change) */
    body:before{
      content:"";
      position:fixed;
      inset:0;
      pointer-events:none;
      background:
        radial-gradient(900px 380px at 10% 10%, rgba(255,255,255,.14), transparent 60%),
        radial-gradient(700px 320px at 90% 25%, rgba(255,255,255,.10), transparent 60%),
        radial-gradient(700px 340px at 50% 95%, rgba(255,255,255,.08), transparent 60%);
      opacity:.95;
    }

    .top{
      background: rgba(36,0,100,.92); /* ✅ same theme but nicer */
      color:#fff;
      padding:14px 0;
      border-bottom: 1px solid rgba(255,255,255,.14);
      backdrop-filter: blur(6px);
      position:sticky;
      top:0;
      z-index:10;
    }

    .wrap{width:1050px;max-width:94vw;margin:0 auto; position:relative;}

    .top-inner{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      flex-wrap:wrap;
    }

    a{color:#bfdbfe;text-decoration:none}
    a:hover{text-decoration:underline;color:#ffffff}

    .card{
      background:#fff;
      border:1px solid rgba(229,231,235,.9);
      border-radius:14px;
      padding:18px 18px 16px;
      margin:22px auto;
      max-width:760px; /* ✅ looks cleaner */
      box-shadow: 0 18px 45px rgba(0,0,0,.28);
      position:relative;
    }

    label{
      display:block;
      margin:12px 0 6px;
      font-size:14px;
      font-weight:700;
      color:#0f172a;
    }

    input,select,textarea{
      width:100%;
      padding:11px 12px;
      border:1px solid #cbd5e1;
      border-radius:10px;
      outline:none;
      font-size:14px;
      background:#f8fafc; /* ✅ soft input bg */
      transition: border-color .15s ease, box-shadow .15s ease, transform .05s ease;
    }

    textarea{min-height:120px;resize:vertical;line-height:1.35}

    input:focus,select:focus,textarea:focus{
      border-color:#7aa7ff;
      background:#ffffff;
      box-shadow:0 0 0 4px rgba(93,34,255,.14);
    }

    /* ✅ better button */
    .btn{
      display:inline-block;
      margin-top:14px;
      padding:11px 16px;
      border:0;
      border-radius:11px;
      background: linear-gradient(135deg, #2c7be5 0%, #1a5fd0 100%);
      color:#fff;
      font-weight:800;
      cursor:pointer;
      box-shadow: 0 10px 18px rgba(44,123,229,.25);
      transition: transform .12s ease, box-shadow .12s ease, filter .12s ease;
    }
    .btn:hover{
      filter:brightness(1.03);
      transform: translateY(-1px);
      box-shadow: 0 14px 22px rgba(44,123,229,.30);
    }
    .btn:active{transform: translateY(0px);}

    .err{
      padding:10px 12px;
      border-radius:10px;
      background:#fee2e2;
      border:1px solid #fecaca;
      color:#991b1b;
      margin-bottom:12px;
      font-size:14px;
    }

    /* ✅ small spacing improvement on mobile */
    @media (max-width:520px){
      .card{padding:16px}
      .btn{width:100%}
    }
  </style>
</head>

<body>
  <div class="top">
    <div class="wrap top-inner">
      <div><b>AIUB LostLink</b> • Create Post</div>
      <div>
        <a href="index.php">All Posts</a> |
        <a href="index.php?category=Lost">Lost</a> |
        <a href="index.php?category=Found">Found</a> |
        <a href="../../controllers/logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="wrap">
    <div class="card">
      <?php if ($err === '1'): ?>
        <div class="err">Please fill all fields correctly.</div>
      <?php elseif ($err === 'db'): ?>
        <div class="err">Database error while saving post.</div>
      <?php endif; ?>

      <form method="post" action="../../controllers/postCreateCheck.php">
        <label>Category</label>
        <select name="category" required>
          <option value="Lost">Lost</option>
          <option value="Found">Found</option>
        </select>

        <label>Title</label>
        <input type="text" name="title" required placeholder="e.g., Lost Wallet (Black)">

        <label>Location</label>
        <input type="text" name="location" required placeholder="e.g., AIUB Campus A - Library">

        <label>Phone</label>
        <input type="text" name="phone" required placeholder="e.g., 01711-111111">

        <label>Student ID</label>
        <input type="text" name="student_id" required placeholder="e.g., 22-12345-1">

        <label>Description</label>
        <textarea name="description" required placeholder="Write details that help identify the item..."></textarea>

        <button class="btn" type="submit" name="submit">Post</button>
      </form>
    </div>
  </div>
</body>
</html>
