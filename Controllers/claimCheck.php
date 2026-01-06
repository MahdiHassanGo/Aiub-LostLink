<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/sessionCheck.php');
require_once(__DIR__ . '/../models/claimModel.php');
require_once(__DIR__ . '/../models/notificationModel.php');
require_once(__DIR__ . '/../models/postModel.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: ../views/Post/index.php');
    exit;
}

if (!isset($_POST['submit'])) {
    header('location: ../views/Post/index.php');
    exit;
}

$userId = (int)($_SESSION['user']['id'] ?? 0);
if ($userId <= 0) {
    header('location: ../views/Login/login.php');
    exit;
}

$post_id = (int)($_POST['post_id'] ?? 0);
$name    = trim($_POST['claimant_name'] ?? '');
$phone   = trim($_POST['claimant_phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($post_id <= 0 || $name === '' || $phone === '') {
    header('location: ../views/Post/details.php?id=' . $post_id . '&err=1');
    exit;
}

$claim = [
    'post_id'        => $post_id,
    'user_id'        => $userId,
    'claimant_name'  => $name,
    'claimant_phone' => $phone,
    'message'        => $message
];

if (addClaim($claim)) {

    // notify claimant (current user)
    addNotification(
        $userId,
        'claim',
        'Message sent',
        'Your verification message was sent.',
        '../views/ClaimRequest/ClaimRequest.php'
    );

    $post = getPostById($post_id); 
    if ($post && isset($post['user_id'])) {
        $ownerId = (int)$post['user_id'];
        if ($ownerId > 0 && $ownerId !== $userId) {
            addNotification(
                $ownerId,
                'claim',
                'New claim message',
                'Someone sent a verification message on your post.',
                '../views/Post/details.php?id=' . $post_id
            );
        }
    }

    header('location: ../views/Post/details.php?id=' . $post_id . '&msg=claim_sent');
    exit;
}

header('location: ../views/Post/details.php?id=' . $post_id . '&err=db');
exit;
