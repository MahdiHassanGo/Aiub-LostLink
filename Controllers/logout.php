<?php
session_start();
session_unset();
session_destroy();
setcookie('status', '', time() - 3600, '/');
header('location: ../views/login.php');
exit;
