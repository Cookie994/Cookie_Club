<?php
//start session to check if the user is logged in, then delete profile and destroy session
    session_start();
    if(!isset($_SESSION['user'])){
       header("Location: login.php");
    }
    require_once "init.php";
    $id = intval($_SESSION['user']['id']);
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    header("Location: login.php");
?> 