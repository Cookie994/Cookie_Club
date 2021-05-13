<?php require_once "init.php" ?>
<?php
    $id = intval($_REQUEST['id']);
    //checking to see if the genre was send
    if(isset($_REQUEST['name'])){
        $name = trim(filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING));
        //update the table
        $stmt = $db->prepare("UPDATE genre SET genre = ?
                            WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
    }

    //to load a specific genre trough id, default values in the form
    $stmt = $db->prepare("SELECT * FROM genre WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $gen = $res->fetch_assoc();

        
?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>Update genre <?= $gen['genre'] ?></h1><!--Trough id we get a title of the genre we want to change-->
    <form method="post" action="genre-update.php?id=<?= $id ?>">
       <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group col-sm-9">
            <label>Name</label>
            <input id="name" type="text" name="name" class="form-control" value="<?= htmlspecialchars($gen['genre'])?>">
        </div>
        <button type="submit" value="Submit" class="btn btn-success">Save</button>
        <a href="genre-read.php" class="btn btn-secondary">Cancel</a>
        <!-- Only superadmin can delete-->
        <?php if($_SESSION['user']['accesslevel'] >= 10){ ?>
            <button type="button" class="btn btn-danger" onclick=" if(confirm('Delete genre?')){ window.location.assign('genre-delete.php?id=<?= $id ?>'); } ">Delete</button>
        <?php } ?>
    </form>           
<?php include "footer.php" ?>
