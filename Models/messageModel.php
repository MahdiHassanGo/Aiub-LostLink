<?php
require_once(__DIR__ . '/db.php');

function createMessage($senderId, $receiverId, $body) {
  $con = getConnection();
  $senderId = (int)$senderId;
  $receiverId = (int)$receiverId;
  $body = trim((string)$body);

  if ($senderId <= 0 || $receiverId <= 0 || $body === '') return false;

  $body = mysqli_real_escape_string($con, $body);

  $sql = "INSERT INTO messages (sender_id, receiver_id, body, is_read)
          VALUES ($senderId, $receiverId, '$body', 0)";
  return mysqli_query($con, $sql);
}

function getUserById($id) {
  $con = getConnection();
  $id = (int)$id;

  $sql = "SELECT id, username, email FROM users WHERE id=$id LIMIT 1";
  $res = mysqli_query($con, $sql);
  if ($res) {
    $row = mysqli_fetch_assoc($res);
    if ($row) return $row;
  }
  return null;
}

function getInboxChats($userId) {
  $con = getConnection();
  $userId = (int)$userId;

  $sql = "SELECT m.id, m.sender_id, m.receiver_id, m.body, m.is_read, m.created_at,
                 u1.username AS sender_name,
                 u2.username AS receiver_name
          FROM messages m
          JOIN users u1 ON u1.id = m.sender_id
          JOIN users u2 ON u2.id = m.receiver_id
          WHERE m.sender_id = $userId OR m.receiver_id = $userId
          ORDER BY m.created_at DESC";

  $result = mysqli_query($con, $sql);
  $chats = [];

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $otherId = ($row['sender_id'] == $userId) ? (int)$row['receiver_id'] : (int)$row['sender_id'];
      if (isset($chats[$otherId])) continue;

      $otherName = ($row['sender_id'] == $userId) ? $row['receiver_name'] : $row['sender_name'];
      $isUnread = ((int)$row['is_read'] === 0 && (int)$row['receiver_id'] === $userId);

      $chats[$otherId] = [
        'id' => $otherId,
        'name' => $otherName,
        'last_message' => $row['body'],
        'created_at' => $row['created_at'],
        'is_read' => $isUnread ? 0 : 1,
        'link' => '/WebTechnology-Project/Controllers/messagesCheck.php?user_id=' . $otherId
      ];
    }
  }

  return array_values($chats);
}

function getThreadMessages($userId, $otherId) {
  $con = getConnection();
  $userId = (int)$userId;
  $otherId = (int)$otherId;

  $sql = "SELECT m.id, m.sender_id, m.receiver_id, m.body, m.is_read, m.created_at,
                 u.username AS sender_name
          FROM messages m
          JOIN users u ON u.id = m.sender_id
          WHERE (m.sender_id = $userId AND m.receiver_id = $otherId)
             OR (m.sender_id = $otherId AND m.receiver_id = $userId)
          ORDER BY m.created_at ASC";

  $result = mysqli_query($con, $sql);
  $list = [];

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) $list[] = $row;
  }

  return $list;
}

function markThreadRead($userId, $otherId) {
  $con = getConnection();
  $userId = (int)$userId;
  $otherId = (int)$otherId;

  $sql = "UPDATE messages
          SET is_read = 1
          WHERE receiver_id = $userId AND sender_id = $otherId";
  return mysqli_query($con, $sql);
}
