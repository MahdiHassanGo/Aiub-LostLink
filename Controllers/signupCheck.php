<?php
    session_start();

    if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if($username == "" || $password == "" || $email == ""){
        echo "null username/password/email";
    }else{
        $user = ['username'=> $username, 'password'=> $password, 'email'=> $email];
        $_SESSION['user'] = $user;
        header('location: ../views/login.php');
    }
    }else{
        header('location: ../views/signup.php');
    }
?>