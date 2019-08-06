<?php require_once "init.php" ?>
<?php
    //checking to see if the product was send
    if(isset($_REQUEST['name'])){
        $name = trim(filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING));
        
        //insert into table
        $stmt = $db->prepare("INSERT INTO genre (genre) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        
        //rederect to list
        header("Location: genre-read.php");
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
            <input id="name" type="text" name="name" class="form-control">
        </div>
        <button class="btn btn-success">Save</button>
        <a href="genre-read.php" class="btn btn-danger">Cancel</a>
    </form>           
<?php include "footer.php" ?>