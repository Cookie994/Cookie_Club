<?php require_once "init.php" ?>
<?php
    //checking to see if the news was send
    if(isset($_REQUEST['title'])){
        $title = trim(filter_var($_REQUEST['title'], FILTER_SANITIZE_STRING));
        $sum = trim(filter_var($_REQUEST['summary'], FILTER_SANITIZE_STRING));
        $cont = trim(filter_var($_REQUEST['content'], FILTER_SANITIZE_STRING));
        $date = $_REQUEST['date'];
        $date_val = date("Y-m-d", strtotime($date));
        $id = uniqid(); /*this function is for the name of the picture, every time a new product is
        uploaded with a new picture, $id is assigned as it's name in the base so it has to be different
        every time and unique*/
        
        /*checking to see if the picture is set, if it is $uploadOk is defined as true and we assign
        an empty array to store mistakes (if there are any)*/
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
                $tmpName = "$id.$ext"; //name of the picture
                $destination = "$dir/$tmpName";
                move_uploaded_file($_FILES['picture']['tmp_name'], $destination);
            }

        }
        //insert into table
        $stmt = $db->prepare("INSERT INTO news (title, summary, content, date, picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $sum, $cont, $date_val, $tmpName);
        $stmt->execute();

        //rederect to list
        header("Location: news-read.php");
        
        $stmt->close();
        
    }

?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>Create new</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group col-sm-9">
            <label>Title</label>
            <input id="title" type="text" name="title" class="form-control">
        </div>
        <div class="form-group col-sm-3">
            <label>Date</label>
            <input id="date" type="date" value="2019-09-20" name="date" class="form-control">
        </div>
        <div class="form-group col-sm-9">
            <label>Summary</label>
            <textarea id="summary" name="summary" class="form-control"></textarea>
        </div>
        <div class="form-group col-sm-9">
            <label>Content</label>
            <textarea id="content" name="content" class="form-control"></textarea>
        </div>
        <div class="form-group col-sm-4">
            <label>Picture</label>
            <input type="file" name="picture" class="form-control">
        </div>
        <button class="btn btn-success">Save</button>
        <a href="news-read.php" class="btn btn-danger">Cancel</a>
    </form>

<?php include "footer.php" ?>