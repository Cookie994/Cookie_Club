<?php require_once "init.php" ?>
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
        $id = false; //set id like there is no that email in db
        if(!empty($_POST['email'])){
            $ename = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
            $email = filter_var($ename, FILTER_VALIDATE_EMAIL);
            
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            
            if($res->num_rows > 0){ //if there are rows in result set id to id that matches email address
                $result = $res->fetch_assoc();
                $id = intval($result['id']);
            } else {
                echo "<script>alert('The submitted email address does not match those on file');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Please enter your email address');</script>";
        }
        if($id){//if there is an id in db set temp pass
            $tempPass = substr(md5(uniqid(rand(), true)), 3, 10);
            
            //crypt temp pass so that we can get a match
            $hashFormat = "$2y$10$";
            $salt = "idontknowwhattoputhere";
            $hash_and_salt = $hashFormat . $salt;
            $temp_pas = crypt($tempPass, $hash_and_salt);
            
            $stmt = $db->prepare("UPDATE users SET password = ?
                                    WHERE id = ?");
            $stmt->bind_param("si", $temp_pas, $id);
            $stmt->execute();
            
            if($stmt->affected_rows == 1){//if affected row is 1, send email
                $mail = new PHPMailer(true);

                try {
                    $mail->SMTPDebug = 0;                                       
                    $mail->isSMTP();                                          
                    $mail->Host       = 'smtp.host.net';  //smtp host
                    $mail->SMTPAuth   = true;                                   
                    $mail->Username   = 'someone@mail.com';                    
                    $mail->Password   = 'password';                              
                    $mail->SMTPSecure = 'ssl';                                  
                    $mail->Port       = 465;                                   

                    //Recipients
                    $mail->setFrom('sender@mail.com'); //sender
                    $mail->addAddress($email); // Add a recipient

                    // Content
                    $mail->isHTML(false);                                  
                    $mail->Subject = 'Your temporary password for Cookie Club';
                    $mail->Body    = "Please, log in using this password $tempPass. Then you can change your password in your profile page.";
                    

                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                
                echo "<h3>Check your email</h3>";
                $stmt->close();
            } else {
                echo "Error";
            }
        }
    }
?>      
<?php include "includes/head.php" ?>
<?php include "includes/nav.php" ?>
<body>
    <div class="container">
        <h1>Reset your password</h1>
        <form method="post" action="">
            <div class="form-group">
                <label>Email address</label>
                <input id="email" type="text" name="email" class="form-control">
            </div>
            <button class="btn btn-primary">Change password</button>
            <a href="login.php" class="btn btn-danger">Cancel</a>
        </form>
<?php include "includes/footer.php" ?>