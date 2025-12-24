<?php

    $host = "127.0.0.1";
    $dbname = "aiub_lostlink";
    $dbuser = "root";
    $dbpass = "";

    function getConnection(){
        global $host;
        global $dbname;
        global $dbpass;

        return $con = mysqli_connect($host, $GLOBALS['dbuser'], $dbpass, $dbname);
    }

?>