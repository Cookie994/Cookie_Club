<?php 
//first include nav because we have started a session there, then check if the user is logged in
    include "includes/nav.php";

    if(!isset($_SESSION['user'])){
       header("Location: login.php");
    }
?>
<?php require_once "init.php" ?>
<?php
    $id = intval($_SESSION['user']['id']);
    $errors = [];//set array to collect errors
    if($_SERVER['REQUEST_METHOD'] == 'POST'){ //submitted via post, then sanitize and validate input
        $name = trim(filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING));
        $lname = trim(filter_var($_REQUEST['lname'], FILTER_SANITIZE_STRING));
        $uname = trim(filter_var($_REQUEST['u'], FILTER_SANITIZE_STRING));
        $ename = trim(filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL));
        $email = filter_var($ename, FILTER_VALIDATE_EMAIL);
        
        //if we don't have anything in array, crypt the password and send data to db
        if(count($errors) == 0){
            //update table
            $stmt = $db->prepare("UPDATE users SET name = ?, lastname = ?, email = ?, username = ?
                                WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $lname, $email, $uname, $id);
            $stmt->execute();
            //rederect to profile
            header("Location: profile.php");
            $stmt->close();
        }
    }
    
?>
         
<script>
//to check is a username is available
    function checkUser() {
        var u = document.getElementById('u').value;
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                var status = JSON.parse(this.responseText);
                if(status == 'free') {
                    document.getElementById('u').style.borderColor = 'green';
                } else {
                    document.getElementById('u').style.borderColor = 'red';
                    alert("Username already taken");
                }
            }
        }
        xhttp.open('GET', 'checkUsername.php?username='+u, true);
        xhttp.send();
    }
    
</script>
<?php include "includes/head.php" ?>
<body>
    <div class="container">
        <h1>Edit profile</h1>
         <?php
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $user = $res->fetch_assoc();
        ?>
        <form method="post" action="">
            <div class="form-group">
                <label>Name</label>
                <input id="n" type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'])?>">
            </div>
            <div class="form-group">
                <label>Lastname</label>
                <input id="ln" type="text" name="lname" class="form-control" value="<?= htmlspecialchars($user['lastname'])?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input id="e" type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'])?>">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input id="u" onkeyup="checkUser()" type="text" name="u" class="form-control" value="<?= htmlspecialchars($user['username'])?>">
            </div>
            <button class="btn btn-primary">Save</button>
            <a href="profile.php" class="btn btn-danger">Cancel</a>
        </form>
<?php include "includes/footer.php" ?>