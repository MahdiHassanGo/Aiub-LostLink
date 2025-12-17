<?php
    session_start();
    //print_r($_POST);
    //var_dump($_POST);

    if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $password = $_POST['password'];


    if($username == "" || $password == ""){
        echo "null username/password";
    }else{
        if($username == $_SESSION['user']['username'] && $password == $_SESSION['user']['password']){
            //echo "Login Success!";
            //$_SESSION['status'] = true;
            setcookie('status', 'true', time()+3000, '/');
            $_SESSION['username'] = $username;
            header('location: ../views/home.php');
        }else{
            echo "invalid username/password";
        }
    }
    }else{
        header('location: ../views/login.php');
    }
?>