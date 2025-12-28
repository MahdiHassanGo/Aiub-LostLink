<?php
session_start();
require_once('../models/userModel.php');

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if($username=="" || $password=="" || $email==""){
        echo "null username/password/email";
        exit;
    }

    $user = ['username'=>$username, 'password'=>$password, 'email'=>$email];

    if(addUser($user)){
        header('location: ../views/login.php');
    }else{
        echo "Registration failed!";
    }
}else{
    header('location: ../views/signup.php');
}
?>
