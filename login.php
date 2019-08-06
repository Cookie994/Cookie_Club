<?php require_once "init.php" ?>
<?php
    if(isset($_REQUEST['u'])){
        $username = trim(filter_var($_REQUEST['u'], FILTER_SANITIZE_STRING));
        $password = trim(filter_var($_REQUEST['p'], FILTER_SANITIZE_STRING));
        
        //salt is necessary for passwords to match
        $hashFormat = "$2y$10$";
        $salt = "idontknowwhattoputhere";
        $hash_and_salt = $hashFormat . $salt;
        $pass = crypt($password, $hash_and_salt);
        
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $pass);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        
        if($user){ //if there is a user in db, start session
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['loggedin'] = true;
            header("Location: index.php");
            die();
        } else {
            echo "<script>alert('Invalid username or password')</script>";
        }
        
        $stmt->close();
    }
    
?>
<?php include "includes/head.php" ?>
<body>
    <?php include "includes/nav.php" ?>
    <div class="container">
        <h1>Sign in</h1>
        <form method="post" action="login.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="u" class="form-control">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="p" class="form-control" readonly onfocus="this.removeAttribute('readonly');">
            </div>
            <button class="btn btn-primary">Sign in</button>
            <a href="index.php" class="btn btn-danger">Cancel</a>
            <a href="forgotPass.php">Forgot your password?</a>
        </form>
<?php include "includes/footer.php" ?>