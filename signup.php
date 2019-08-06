<?php require_once "init.php" ?>
<?php
    $errors = [];//set array to collect errors
    if($_SERVER['REQUEST_METHOD'] == 'POST'){ //submitted via post, then sanitize and validate input
        $name = trim(filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING));
        $lname = trim(filter_var($_REQUEST['lname'], FILTER_SANITIZE_STRING));
        $uname = trim(filter_var($_REQUEST['u'], FILTER_SANITIZE_STRING));
        $ename = trim(filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL));
        $email = filter_var($ename, FILTER_VALIDATE_EMAIL);
        $pass1 = trim(filter_var($_REQUEST['p'], FILTER_SANITIZE_STRING));
        $pass2 = trim(filter_var($_REQUEST['p2'], FILTER_SANITIZE_STRING));
        
        //if some input is empty create a value in array
        if(empty($name)){
            $errors[] = "Please enter your name";
        }
        if(empty($lname)){
            $errors[] = "Please enter your lastname";
        }
        if(empty($uname)){
            $errors[] = "Please enter your username";
        }
        if(empty($email)){
            $errors[] = "Please enter your email";
        }
        if(empty($pass1)){
            $errors[] = "Please enter your password";
        }
        if(empty($pass2)){
            $errors[] = "Please re-enter your password";
        }
        //if passwords don't match, create value in array
        if($pass1 !== $pass2){
            $errors[] = "Error, passwords do not match";
        }
        //if we don't have anything in array, crypt the password and send data to db
        if(count($errors) == 0){
            //crypting the password with blowfish
            $hashFormat = "$2y$10$";
            $salt = "idontknowwhattoputhere";
            $hash_and_salt = $hashFormat . $salt;
            $pass = crypt($pass1, $hash_and_salt);
            
            //set access to 1 because anyone who is registered trough this form is just a costummer and his accesslevel is 1
            $access = 1;
            //insert into table
            $stmt = $db->prepare("INSERT INTO users (name, lastname, email, username, password, accesslevel) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $lname, $email, $uname, $pass, $access);
            $stmt->execute();

            //rederect to login
            header("Location: login.php");
            $stmt->close();
        } /*
I wanted to dispaly every error if a field is left empty, however I wasn't able to do it without alerting previous values of array (if alert is in function) or having page on reload while alert pop-up is not closed (if alert isn't in function). Any suggestions?

            else {
            ?>
            <script>
                var error = JSON.parse('<?php echo json_encode($errors); ?>');
                    alert(error.join('\n'));
            </script>
            <?php
            
        }*/
    }
?>
         
<script>
//instead, I made a function that will alert that all fields are required and that passwords need to match
    function errors(){
        var n = document.getElementById("n");
        var ln = document.getElementById("ln");
        var e = document.getElementById("e");
        var u = document.getElementById("u");
        var p = document.getElementById("p");
        var p2 = document.getElementById("p2");
        
        if(n.value == "" || ln.value == "" || e.value == "" || u.value == "" || p.value == ""){
            alert("All fields are required");
            return false;
        } else if(p.value !== p2.value){
            p.style.borderColor = 'red';
            p2.style.borderColor = 'red';
            alert("Error, passwords don't match");
            return false;
        } else {
            return true;
        }
    }

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
    <?php include "includes/nav.php" ?>
    <div class="container">
        <h1>Sign up</h1>
        <form method="post" action="signup.php" onsubmit="return errors();">
            <div class="form-group">
                <label>Name*</label>
                <input id="n" type="text" name="name" class="form-control">
            </div>
            <div class="form-group">
                <label>Lastname*</label>
                <input id="ln" type="text" name="lname" class="form-control">
            </div>
            <div class="form-group">
                <label>Email*</label>
                <input id="e" type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Username*</label>
                <input id="u" onkeyup="checkUser()" type="text" name="u" class="form-control">
            </div>
            <div class="form-group">
                <label>Password*</label>
                <input id="p" type="password" name="p" class="form-control" readonly onfocus="this.removeAttribute('readonly');">
            </div>
            <div class="form-group">
                <label>Repeat password*</label>
                <input id="p2" type="password" name="p2" class="form-control">
            </div>
            <button class="btn btn-primary">Sign up</button>
            <a href="index.php" class="btn btn-danger">Cancel</a>
        </form>
<?php include "includes/footer.php" ?>