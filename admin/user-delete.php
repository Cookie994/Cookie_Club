<?php
    $ACCESSLEVEL = 10;
    require_once "init.php";
    $id = intval($_REQUEST['id']);
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: user-read.php");
?> 