Efrath Hossain Shihab
e…
Online
This is the start of the Web Technologies private channel. 
G… — 11/4/2025 11:48 PM
🚽 🏃‍♂️
G… — 12/9/2025 11:38 PM
abdullah.farhan.azad@gmail.com
SynT3C

 — 12/9/2025 11:47 PM
https://whereisit-88cd2.web.app/
<html lang="en">
<head>
    <title>Admin Login</title>
</head>
<body>
    <form method="post" action="#" onsubmit="return validateAdminLogin();">
Expand
adminLogin.html
1 KB
function showError(id, message) {
    var el = document.getElementById(id);
    if (el) {
        el.innerHTML = message;
    }
}
Expand
adminLoginValidation.js
1 KB
<html lang="en">
<head>
    <title>Found Item</title>
</head>
<body>
    <form method="post" action="#" onsubmit="return validateFoundItem();">
Expand
FoundItemPage.html
2 KB
function showError(id, message) {
    var el = document.getElementById(id);
    if (el) {
        el.innerHTML = message;
    }
}
Expand
foundItemValidation.js
2 KB
<html lang="en">
<head>
    <title>User Login</title>
</head>
<body>
    <form method="post" action="#" onsubmit="return validateLogin();">
Expand
login.html
1 KB
function showError(id, message) {
    var el = document.getElementById(id);
    if (el) {
        el.innerHTML = message;
    }
}
Expand
loginValidation.js
1 KB
function showError(id, message) {
    var el = document.getElementById(id);
    if (el) {
        el.innerHTML = message;
    }
}
Expand
lostItemValidation.js
2 KB
<html lang="en">
<head>
    <title>Post Lost Item</title>
</head>
<body>
    <form method="post" action="#" onsubmit="return validateLostItem();">
Expand
PostLostItem.html
2 KB
<html lang="en">
<head>
    <title>Registration</title>
</head>
<body>
    <form method="post" action="#" onsubmit="return validateRegistration();">
Expand
registration.html
2 KB
function showError(id, message) {
    var el = document.getElementById(id);
    if (el) {
        el.innerHTML = message;
    }
}
Expand
registrationValidation.js
2 KB
SynT3C

 — 12/26/2025 8:16 PM
https://www.notion.so/Lost-and-Found-2a211ce1323e808792f3d41165c293e6
https://trello.com/b/qFhihfbQ/web-technologies-project
Trello
Organize anything, together. Trello is a collaboration tool that organizes your projects into boards. In one glance, know what's being worked on, who's working on what, and where something is in a process.
SynT3C

 — 12/26/2025 8:45 PM
https://whereisit-88cd2.web.app/
Efrath Hossain Shihab

 — Yesterday at 2:08 PM
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 28, 2025 at 09:08 AM
Expand
AIUBLostLink.sql
7 KB
SynT3C

 — 11:28 PM
<?php
require_once('../sessionCheck.php');
$err = $_GET['err'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
Expand
create.php
3 KB
<?php
require_once('../sessionCheck.php');
require_once('../../models/postModel.php');

$id = intval($_GET['id'] ?? 0);
$post = ($id > 0) ? getPostById($id) : false;
Expand
details.php
5 KB
<?php
require_once('../sessionCheck.php');
require_once('../../models/postModel.php');

$category = $_GET['category'] ?? null;
$msg = $_GET['msg'] ?? null;
Expand
index.php
4 KB

<?php
require_once('sessionCheck.php');
require_once('../models/postModel.php');

$result = getAllPosts();
Expand
ShowPost.php
4 KB
﻿
<?php
require_once('../sessionCheck.php');
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
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f6fb;color:#111}
    .top{background:#0f172a;color:#fff;padding:14px 0}
    .wrap{width:1050px;max-width:94vw;margin:0 auto}
    .top-inner{display:flex;align-items:center;justify-content:space-between;gap:12px}
    a{color:#93c5fd;text-decoration:none}
    a:hover{text-decoration:underline}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:18px 0}
    label{display:block;margin:10px 0 6px;font-size:14px}
    input,select,textarea{width:100%;padding:10px;border:1px solid #bbb;border-radius:10px}
    textarea{min-height:120px;resize:vertical}
    .btn{display:inline-block;margin-top:12px;padding:10px 14px;border:0;border-radius:10px;background:#2c7be5;color:#fff;font-weight:700;cursor:pointer}
    .btn:hover{background:#1a5fd0}
    .err{padding:10px;border-radius:10px;background:#fee2e2;border:1px solid #fecaca;color:#991b1b;margin-bottom:10px}
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