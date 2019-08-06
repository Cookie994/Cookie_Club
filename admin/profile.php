<?php require_once "init.php" ?>
<?php include "head.php" ?>
<body>
    <?php include "nav.php" ?>
    <div class="container">
        <h1>Profile</h1>
         <?php
        //log in to access this page
            if(!isset($_SESSION['user'])){
               header("Location: login.php");
            } else {
                $id = intval($_SESSION['user']['id']);
                $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                while($user = $res->fetch_assoc()){
                ?>
                    <p>Name: <?= $user['name'] ?></p>
                    <p>Lastname: <?= $user['lastname'] ?></p>
                    <p>Username: <?= $user['username'] ?></p>
                    <p>Email: <?= $user['email'] ?></p>
                    <p>Accesslevel:
                        <?php
                            switch($user['accesslevel']){
                                case 1: echo "Registered";
                                break;
                                case 5: echo "Administrator";
                                break;
                                case 10: echo "Superadmin";
                                break;
                            }
                        ?>
                    </p>
                <?php
                    }
            }
            ?>
            <script>
                //confirmation box to ask if you really want to delete your profile
                function Delete(){
                    var ask = confirm("Are you sure you want to delete your profile?");
                    if(ask == true){
                        location.href = "profile-delete.php";
                        alert("You have successfully deleted your profile");
                    } else {
                        location.href = "profile.php";
                    }
                }
            </script>
            <a href="profile-update.php" class="btn btn-success">Edit profile</a>
            <a href="changePassword.php" class="btn btn-danger">Change password</a>
            <button onclick="Delete()" class="btn btn-danger">Delete profile</button>
<?php include "footer.php" ?>