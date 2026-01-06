<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../controllers/adminCheck.php');
require_once(__DIR__ . '/../../models/userModel.php');

$msg = $_GET['msg'] ?? null;

$users = getAllUsers();

$currentUserId = $_SESSION['user']['id'] ?? $_SESSION['user']['user_id'] ?? $_SESSION['id'] ?? null;

function esc($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Admin User Management</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0; position:relative;}
    .wrap{width:1100px;max-width:94vw;margin:0 auto}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 8px 22px rgba(15,23,42,.06);padding:16px;margin:18px 0}
    h1{margin:0;font-size:20px}
    .msg{margin:12px 0;padding:10px 12px;border-radius:10px;border:1px solid #dbeafe;background:#eff6ff;color:#1e3a8a}
    .msg.bad{border-color:#fecaca;background:#fef2f2;color:#991b1b}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px 12px;border-bottom:1px solid #eef2f7;text-align:left;vertical-align:middle}
    th{font-size:13px;color:#334155;background:#f8fafc}
    .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;border:1px solid #e2e8f0;background:#f8fafc}
    .badge.admin{border-color:#c7d2fe;background:#eef2ff}
    .badge.moderator{border-color:#a7f3d0;background:#ecfdf5}
    .badge.user{border-color:#e5e7eb;background:#f9fafb}
    select{padding:8px 10px;border:1px solid #d1d5db;border-radius:10px;background:#fff}
    button{padding:8px 12px;border:0;border-radius:10px;background:#0ea5e9;color:#fff;cursor:pointer}
    button:disabled{opacity:.6;cursor:not-allowed}
    .small{font-size:12px;color:#64748b}
    .row-actions{display:flex;gap:10px;align-items:center}
    .back{position:absolute;top:18px;right:24px;font-size:14px;}
    .back a{color:#38bdf8;text-decoration:none}
  </style>
</head>
<body>

<div class="top">
  <div class="wrap">
    <h1>Admin User Management</h1>
    <div class="small">View users and change roles (User / Admin / Moderator).</div>
  </div>
  <div class="back">
    Back to <a href="../Post/index.php">Home</a>
  </div>
</div>

<div class="wrap">
  <div class="card">

    <?php if ($msg): ?>
      <?php
        $isBad = in_array($msg, ['update_failed','unauthorized','cannot_change_self'], true);
        $text = $msg;
        if ($msg === 'role_updated') $text = 'Role updated successfully.';
        if ($msg === 'update_failed') $text = 'Failed to update role. Please try again.';
        if ($msg === 'cannot_change_self') $text = 'You cannot change your own role.';
        if ($msg === 'unauthorized') $text = 'Unauthorized access.';
      ?>
      <div class="msg <?php echo $isBad ? 'bad' : ''; ?>"><?php echo esc($text); ?></div>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Email</th>
          <th>Current Role</th>
          <th>Change Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($users)): ?>
        <?php $i=1; foreach ($users as $u): ?>
          <?php
            $role = $u['role'] ?? 'User';
            $badgeClass = 'user';
            if ($role === 'Admin') $badgeClass = 'admin';
            if ($role === 'Moderator') $badgeClass = 'moderator';

            $isSelf = ($currentUserId !== null && (int)$u['id'] === (int)$currentUserId);
            $formId = 'f_role_' . (int)$u['id'];
          ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo esc($u['username']); ?></td>
            <td><?php echo esc($u['email']); ?></td>
            <td>
              <span class="badge <?php echo esc($badgeClass); ?>">
                <?php echo esc($role); ?><?php echo $isSelf ? ' (You)' : ''; ?>
              </span>
            </td>

            <td>
              <select name="role" form="<?php echo esc($formId); ?>" <?php echo $isSelf ? 'disabled' : ''; ?>>
                <option value="User" <?php echo ($role==='User'?'selected':''); ?>>User</option>
                <option value="Moderator" <?php echo ($role==='Moderator'?'selected':''); ?>>Moderator</option>
                <option value="Admin" <?php echo ($role==='Admin'?'selected':''); ?>>Admin</option>
              </select>
            </td>

            <td>
              <form id="<?php echo esc($formId); ?>" method="POST" action="../../controllers/adminManagementCheck.php">
                <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>" />
                <div class="row-actions">
                  <button type="submit" name="submit" <?php echo $isSelf ? 'disabled' : ''; ?>>Update</button>
                  <span class="small">ID: <?php echo (int)$u['id']; ?></span>
                </div>
              </form>
            </td>

          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6">No users found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>

  </div>
</div>

</body>
</html>
