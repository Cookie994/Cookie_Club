<?php
    //accesslevel for admin pages is set to 5, so anyone who is below is not allowed
    if(!isset($ACCESSLEVEL)){
        $ACCESSLEVEL = 5;
    }

    //start session, check if user is logged in and has accesslevel > 5
    session_start();
    if(!$_SESSION['loggedin'] or $_SESSION['user']['accesslevel'] < $ACCESSLEVEL){
        header("Location: login.php");
        echo "Access denied";
        die();
    }

    $db = new mysqli('localhost', 'root', '' , 'video');
    $db->query("set names utf8");
//connection with base
?>