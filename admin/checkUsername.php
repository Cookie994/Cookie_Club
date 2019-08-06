<?php
    require "init.php";
    $u = $db->real_escape_string($_REQUEST['username']);
    $res = $db->query("select * from users where username='$u' ");
    $user = $res->fetch_assoc();
    if($user){
        $status = 'taken';
    } else {
        $status = 'free';
    }
    echo json_encode($status);
?>
