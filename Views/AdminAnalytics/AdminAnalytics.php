<?php
require_once('../sessionCheck.php');
require_once('/WebTechnology-Project/controllers/adminAnalyticsChecker.php
');

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Admin Analytics</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0;position:relative}
    .wrap{width:1100px;max-width:94vw;margin:0 auto}
    h1{margin:0;font-size:20px}
    .sub{font-size:12px;color:#cbd5e1;margin-top:4px}
    .back{position:absolute;top:18px;right:24px;font-size:14px}
    .back a{color:#38bdf8;text-decoration:none}
    .back a:hover{text-decoration:underline}

    .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:18px 0}
    @media(max-width:1000px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:560px){.grid{grid-template-columns:1fr}}

    .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 8px 22px rgba(15,23,42,.06);padding:16px}
    .kpi-title{font-size:13px;color:#475569;margin:0 0 8px}
    .kpi-value{font-size:28px;font-weight:800;margin:0}
    .kpi-foot{font-size:12px;color:#64748b;margin-top:10px}

    .charts{display:grid;grid-template-columns:1.2fr .8fr;gap:14px;margin:10px 0 26px}
    @media(max-width:1000px){.charts{grid-template-columns:1fr}}
    .chart-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 8px 22px rgba(15,23,42,.06);padding:16px}
    .chart-title{margin:0 0 10px;font-size:14px;color:#0f172a;font-weight:800}

    .msg{margin:12px 0;padding:10px 12px;border-radius:10px;border:1px solid #fecaca;background:#fef2f2;color:#991b1b}
    .small{font-size:12px;color:#64748b;margin-top:8px}
    canvas{width:100% !important;height:320px !important}
  </style>
</head>
<body>

<div class="top">
  <div class="wrap">
    <h1>Admin Analytics</h1>
    <div class="sub">Users + Posts analytics (live DB). Zero values are supported.</div>
  </div>
  <div class="back">
    Back to <a href="/WebTechnology-Project/views/Post/index.php">Home</a>
  </div>
</div>

<div class="wrap">

  <?php if (!empty($dbErrorMsg)): ?>
    <div class="msg"><?php echo esc($dbErrorMsg); ?></div>
  <?php endif; ?>

  <!-- KPI CARDS -->
  <div class="grid">
    <div class="card">
      <p class="kpi-title">Total Users</p>
      <p class="kpi-value"><?php echo (int)($stats['users_total'] ?? 0); ?></p>
      <div class="kpi-foot">All registered accounts.</div>
    </div>

    <div class="card">
      <p class="kpi-title">Admins</p>
      <p class="kpi-value"><?php echo (int)($stats['users_admin'] ?? 0); ?></p>
      <div class="kpi-foot">Role = Admin.</div>
    </div>

    <div class="card">
      <p class="kpi-title">Total Posts</p>
      <p class="kpi-value"><?php echo (int)($stats['posts_total'] ?? 0); ?></p>
      <div class="kpi-foot">Lost + Found.</div>
    </div>

    <div class="card">
      <p class="kpi-title">Lost / Found</p>
      <p class="kpi-value">
        <?php echo (int)($stats['posts_lost'] ?? 0); ?> /
        <?php echo (int)($stats['posts_found'] ?? 0); ?>
      </p>
      <div class="kpi-foot">Category split.</div>
    </div>
  </div>

  <!-- CHARTS -->
  <div class="charts">
    <div class="chart-card">
      <div class="chart-title">Posts Trend (Last 7 Days)</div>
      <canvas id="postsTrend"></canvas>
      <div class="small">Always shows 7 days (even if counts are 0).</div>
    </div>

    <div class="chart-card">
      <div class="chart-title">Users by Role</div>
      <canvas id="usersByRole"></canvas>
      <div class="small">User / Moderator / Admin distribution.</div>
    </div>
  </div>

  <div class="charts">
    <div class="chart-card">
      <div class="chart-title">Posts by Category</div>
      <canvas id="postsByCategory"></canvas>
      <div class="small">Lost vs Found.</div>
    </div>

    <div class="chart-card">
      <div class="chart-title">Quick Numbers</div>
      <div class="small">
        <b>Users:</b>
        User = <?php echo (int)($stats['users_user'] ?? 0); ?>,
        Moderator = <?php echo (int)($stats['users_moderator'] ?? 0); ?>,
        Admin = <?php echo (int)($stats['users_admin'] ?? 0); ?><br><br>

        <b>Posts:</b>
        Total = <?php echo (int)($stats['posts_total'] ?? 0); ?>,
        Lost = <?php echo (int)($stats['posts_lost'] ?? 0); ?>,
        Found = <?php echo (int)($stats['posts_found'] ?? 0); ?>
      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const usersRoleLabels = <?= json_encode($charts['users_role_labels'] ?? ['User','Moderator','Admin']) ?>;
  const usersRoleData   = <?= json_encode($charts['users_role_data'] ?? [0,0,0]) ?>;

  const postsCatLabels  = <?= json_encode($charts['posts_cat_labels'] ?? ['Lost','Found']) ?>;
  const postsCatData    = <?= json_encode($charts['posts_cat_data'] ?? [0,0]) ?>;

  const trendLabels     = <?= json_encode($charts['trend_labels'] ?? ['No data']) ?>;
  const trendData       = <?= json_encode($charts['trend_data'] ?? [0]) ?>;

  // Users by Role (Bar)
  new Chart(document.getElementById('usersByRole'), {
    type: 'bar',
    data: {
      labels: usersRoleLabels,
      datasets: [{
        label: 'Users',
        data: usersRoleData,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });

  // Posts by Category (Doughnut)
  new Chart(document.getElementById('postsByCategory'), {
    type: 'doughnut',
    data: {
      labels: postsCatLabels,
      datasets: [{
        label: 'Posts',
        data: postsCatData,
        borderWidth: 1
      }]
    },
    options: { responsive: true }
  });

  // Posts Trend (Line)
  new Chart(document.getElementById('postsTrend'), {
    type: 'line',
    data: {
      labels: trendLabels,
      datasets: [{
        label: 'Posts per day',
        data: trendData,
        tension: 0.25,
        fill: false,
        borderWidth: 2,
        pointRadius: 3
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });
</script>
</body>
</html>
