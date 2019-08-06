<?php require_once "init.php" ?>
<?php
    //checking to see if the username was set
    if(isset($_REQUEST['name'])){
        $uname = trim(filter_var($_REQUEST['uname'], FILTER_SANITIZE_STRING));
        $name = trim(filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING));
        $lname = trim(filter_var($_REQUEST['lname'], FILTER_SANITIZE_STRING));
        //validate email
        $ename = trim(filter_var($_REQUEST['ename'], FILTER_SANITIZE_EMAIL));
        $email = filter_var($ename, FILTER_VALIDATE_EMAIL);
        //crypting the password with blowfish
        $pass = trim(filter_var($_REQUEST['password'], FILTER_SANITIZE_STRING));
        $hashFormat = "$2y$10$";
        $salt = "idontknowwhattoputhere";
        $hash_and_salt = $hashFormat . $salt;
        $pass = crypt($pass, $hash_and_salt);
        
        $access = $_REQUEST['access'];
        
        //insert into table
        $stmt = $db->prepare("INSERT INTO users (username, name, lastname, email, password, accesslevel) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $uname, $name, $lname, $email, $pass, $access);
        $stmt->execute();
        
        //rederect to list
        header("Location: user-read.php");
        $stmt->close();


    }
?>
<?php include "head.php" ?>
<?php include "nav.php" ?>

<body>
    <h1>Create new</h1>
    <form method="post" action="">
        <div class="form-group col-sm-9">
            <label>Name</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group col-sm-9">
            <label>Lastname</label>
            <input type="text" name="lname" class="form-control">
        </div>
        <div class="form-group col-sm-9">
            <label>Email</label>
            <input type="email" name="ename" class="form-control">
        </div>
           <div class="form-group col-sm-9">
            <label>Username</label>
            <input type="text" name="uname" class="form-control">
        </div>
        <div class="form-group col-sm-9">
            <label>Password</label>
            <input type="text" name="password" class="form-control">
        </div>
        <div class="form-group col-sm-9">
            <label>Accesslevel</label>
            <select name="access" class="form-control">
                <option  value = "1">Registered</option>
                <option  value = "5">Administrator</option>
                <!-- Only super admin can create superadmins-->
                <?php if($_SESSION['user']['accesslevel'] >= 10){?>
                    <option  value = "10">Superadmin</option>
                <?php } ?>
            </select>
        </div>
        <button class="btn btn-success">Save</button>
        <a href="user-read.php" class="btn btn-danger">Cancel</a>
    </form>           
<?php include "footer.php" ?>