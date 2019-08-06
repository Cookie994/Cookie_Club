<?php 
    require_once "init.php";
    $ACCESSLEVEL = 10;
?>
<?php
    $id = intval($_REQUEST['id']);
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
        
        //update table
        $stmt = $db->prepare("UPDATE users SET username = ?, name = ?, lastname = ?, email = ?, password = ?, accesslevel = ?
                            WHERE id = ?");
        $stmt->bind_param("ssssssi", $uname, $name, $lname, $email, $pass, $access, $id);
        $stmt->execute();
        
        //rederect to list
        header("Location: user-read.php");
        $stmt->close();


    }
?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<?php 
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $u = $res->fetch_assoc();
?>
<body>
    <h1>Update user <?php $u['username']; ?></h1>
    <form method="post" action="user-update.php?id=<?= $id ?>">
       <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group col-sm-9">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($u['name'])?>">
        </div>
        <div class="form-group col-sm-9">
            <label>Lastname</label>
            <input type="text" name="lname" class="form-control" value="<?= htmlspecialchars($u['lastname'])?>">
        </div>
        <div class="form-group col-sm-9">
            <label>Email</label>
            <input type="email" name="ename" class="form-control" value="<?= htmlspecialchars($u['email'])?>">
        </div>
           <div class="form-group col-sm-9">
            <label>Username</label>
            <input type="text" name="uname" class="form-control" value="<?= htmlspecialchars($u['username'])?>">
        </div>
        <div class="form-group col-sm-9">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?= htmlspecialchars($u['password'])?>">
        </div>
        <div class="form-group col-sm-9">
            <label>Accesslevel</label>
            <select name="access" class="form-control">
                <option <?php if($u['accesslevel']==1) echo 'selected'; ?> value = "1">Registered</option>
                <option <?php if($u['accesslevel']==5) echo 'selected'; ?> value = "5">Administrator</option>
                <option <?php if($u['accesslevel']==10) echo 'selected'; ?> value = "10">Superadmin</option>
            </select>
        </div>
        <button class="btn btn-success">Save</button>
        <a href="user-read.php" class="btn btn-secondary">Cancel</a>
        <button type="button" class="btn btn-danger" onclick=" if(confirm('Delete user?')){ window.location.assign('user-delete.php?id=<?= $id ?>'); } ">Delete</button>
    </form>           
<?php include "footer.php" ?>