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
