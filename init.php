<?php
//access shouldn't be set, however if there is an accesslevel we check that data in session
    if(isset($ACCESSLEVEL)){
        session_start();
        if(isset($_SESSION['user']) and $_SESSION['user']['accesslevel'] >= $ACCESSLEVEL){
            
        } else {
            echo "Access denied";
            header("Location: login.php");
            die();
        }
    }

    $db = new mysqli('localhost', 'root', '' , 'video');
    $db->query("set names utf8");
//connection with base
?>