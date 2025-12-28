<?php
session_start();
require_once('../models/userModel.php');

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($email == "" || $password == ""){
        echo "null email/password";
        exit;
    }

    $user = login($email, $password);

    if($user){
        setcookie('status', 'true', time()+3000, '/');
        $_SESSION['user'] = $user;
        header('location: ../views/home.php');
    }else{
        echo "invalid email/password";
    }
}else{
    header('location: ../views/login.php');
}
?>
