<?php require_once "init.php" ?>
<?php
    $id = intval($_REQUEST['id']);/*because we already have the news with it's id in the base, there is no need
    to generate a new name for a picture (it can be named like a id from the table)*/
    //checking to see if the news was send
    if(isset($_REQUEST['title'])){
        $title = trim(filter_var($_REQUEST['title'], FILTER_SANITIZE_STRING));
        $sum = trim(filter_var($_REQUEST['summary'], FILTER_SANITIZE_STRING));
        $cont = trim(filter_var($_REQUEST['content'], FILTER_SANITIZE_STRING));
        $date = $_REQUEST['date'];
        $date_val = date("Y-m-d", strtotime($date));
        
        /*checking to see if the picture is set, if it is $uploadOk is defined as true and we assign an empty array to store mistakes (if there are any)*/
    if(isset($_FILES['picture'])){
        $uploadOk = true;
        $status = [];
        /*searching for mistakes, ext in lower cases and only jpg and png, $uploadOK
        false if it's not*/
        $ext = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
        if($ext != 'jpg' and $ext != 'png'){
            $uploadOk = false;
            $status[] = "File extension is not supported";
        }
        if($_FILES['picture']['size'] > 2000000){
            $uploadOk = false;
            $status = "File is bigger than 2MB";
        }
        /*if it's true, assign temporary name and store it in folder(path) */
        if($uploadOk == true){
            $dir = "../product_img";
            $tmpName = "news-$id.$ext"; //name of the picture
            $destination = "$dir/$tmpName";
            move_uploaded_file($_FILES['picture']['tmp_name'], $destination);

        }

    }
        
        //update the table
        $stmt = $db->prepare("UPDATE news SET title = ?, summary = ?, content = ?, date = ?, picture = ?
                            WHERE id = ?");
        $stmt->bind_param("sssssi", $title, $sum, $cont, $date_val, $tmpName, $id);
        $stmt->execute();
        $stmt->close();

    }

    //to load a specific news trough id, default values in the form
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $news = $res->fetch_assoc();
?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>Update news <?= $news['title'] ?></h1> <!--Trough id we get a title of the product we want to change-->
    
    <form method="post" action="news-update.php?id=<?= $id ?>" enctype="multipart/form-data">
       <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group col-sm-9">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($news['title'])?>">
        </div>
        <div class="form-group col-sm-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="<?= $news['date']?>">
        </div>
        <div class="form-group col-sm-9">
            <label>Summary</label>
            <textarea name="summary" class="form-control"><?= htmlspecialchars($news['summary'])?></textarea>
        </div>
        <div class="form-group col-sm-9">
            <label>Content</label>
            <textarea name="content" class="form-control"><?= htmlspecialchars($news['content'])?></textarea>
        </div>
        <div class="form-group col-sm-4">
            <label>Picture</label>
            <input type="file" name="picture" class="form-control">
        </div>
        <div class="form-group col-sm-4">
            <?php if($news['picture']){?>
                    <img src="../product_img/<?= $news['picture']?>">
                    <?php } else{?>
                        No picture
                    <?php } ?>
        </div>
        <button class="btn btn-success">Save</button>
        <a href="news-read.php" class="btn btn-secondary">Cancel</a>
        <!-- Only superadmin can delete-->
        <?php if($_SESSION['user']['accesslevel'] >= 10){ ?>
            <button type="button" class="btn btn-danger" onclick=" if(confirm('Delete news?')){ window.location.assign('news-delete.php?id=<?= $id ?>'); } ">Delete</button>
        <?php } ?>
    </form>

<?php include "footer.php" ?>