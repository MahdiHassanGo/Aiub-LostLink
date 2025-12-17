<?php
   //include('../controllers/sessionCheck.php');
   //include_once('../controllers/sessionCheck.php');
   //require('../controllers/sessionCheck.php');
   require_once('../controllers/sessionCheck.php');
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
</head>
<body>
        <h1>Welcome Home! <?php echo $_SESSION['username'];?> </h1>
        <a href="userlist.php"> User List</a> |
        <a href="../controllers/logout.php"> logout</a>
</body>
</html>