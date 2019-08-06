<?php 
    //nav is fisrt included because I have started a session there
    include "includes/nav.php";

    //check to see if a session is started, if not get redirected to login
    if(!isset($_SESSION['user'])){
       header("Location: login.php");
    }
?>
<?php require_once "init.php" ?>
<?php
    $id = intval($_SESSION['user']['id']);
    $errors = [];//set array to collect errors

    if($_SERVER['REQUEST_METHOD'] == 'POST'){ //submitted via post, then sanitize and validate input 
        //query to get all data from users table (to check old pass)
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        $oldPass = htmlspecialchars($user['password']);
        $pass1 = trim(filter_var($_REQUEST['p'], FILTER_SANITIZE_STRING));
        $pass2 = trim(filter_var($_REQUEST['p2'], FILTER_SANITIZE_STRING));
        $pass3 = trim(filter_var($_REQUEST['p3'], FILTER_SANITIZE_STRING));

        //crypt old pass so that we can get a match
        $hashFormat = "$2y$10$";
        $salt = "idontknowwhattoputhere";
        $hash_and_salt = $hashFormat . $salt;
        $old_pass = crypt($pass3, $hash_and_salt);
        
        //if passwords don't match, create value in array
        if($pass1 !== $pass2){
            $errors[] = "Error, passwords do not match";
        }
        
        //if old pass doesn't match, create value in array
        if($old_pass !== $oldPass){
            $errors[] = "Wrong password";
/* I wanted to make it possible to have a alert box pop out if you have inserted wrong old pass,
however I wasn't able to pull out variables from the if statement and use them in js (local scope), so I made alert box like this, but I don't think it's such a great idea because I have the same issue like in signup.php, page is on reload while alert box is active. Any suggestions? */
            echo '<script> alert("Wrong old password"); </script>';
        }
        //if we don't have anything in array, crypt the password and send data to db
        if(count($errors) == 0){
            //crypting the password with blowfish
            $hashFormat = "$2y$10$";
            $salt = "idontknowwhattoputhere";
            $hash_and_salt = $hashFormat . $salt;
            $pass = crypt($pass1, $hash_and_salt);
            
            //update table
            $stmt = $db->prepare("UPDATE users SET password = ?
                                WHERE id = ?");
            $stmt->bind_param("si", $pass, $id);
            $stmt->execute();
            //rederect to profile
            header("Location: profile.php");
            $stmt->close();
        }
    }
?>
         
<script>
    function errors(){
        var p = document.getElementById("p");
        var p2 = document.getElementById("p2");
        if(p.value !== p2.value){
            p.style.borderColor = 'red';
            p2.style.borderColor = 'red';
            alert("Error, passwords don't match");
            return false;
        } else {
            return true;
        }
    }
</script>
<?php include "includes/head.php" ?>
<body>
    <div class="container">
        <h1>Edit password</h1>
        <form method="post" action="" onsubmit="return errors();">
            <div class="form-group">
                <label>Old Password</label>
                <input id="p3" type="password" name="p3" class="form-control">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input id="p" type="password" name="p" class="form-control">
            </div>
            <div class="form-group">
                <label>Repeat new password</label>
                <input id="p2" type="password" name="p2" class="form-control">
            </div>
            <button class="btn btn-primary">Save</button>
            <a href="profile.php" class="btn btn-danger">Cancel</a>
        </form>
<?php include "includes/footer.php" ?>