<?php require_once "init.php" ?>
<?php
    //checking to see if the product was send
    if(isset($_REQUEST['name'])){
        $name = trim(filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING));
        $year = trim(filter_var($_REQUEST['year'], FILTER_SANITIZE_STRING));
        $des = trim(filter_var($_REQUEST['description'], FILTER_SANITIZE_STRING));
        $price = trim(filter_var($_REQUEST['price'], FILTER_SANITIZE_STRING));
        $type = $_REQUEST['type'];
        $id = uniqid(); /*this function is for the name of the picture, every time a new product is
        uploaded with a new picture, $id is assigned as it's name in the base so it has to be different
        every time and unique*/
        
        if(isset($_REQUEST['recommend'])){
            $recommend = 1;
        } else {
            $recommend = 0;
        }
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
                $status[] = "File is bigger than 2MB";
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
        $stmt = $db->prepare("INSERT INTO products (name, year, description, price, picture, is_recommend, type_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $year, $des, $price, $tmpName, $recommend, $type);
        $stmt->execute();
        
        //rederect to list
        header("Location: products-read.php");
        $stmt->close();


    }
        
    //inserting values of checkbox into products_genre table, for products_id used function to fetch last inserted id from above stmt
   if ( !empty($_POST["genre"]) ) {
        $genre = $_POST["genre"];
        $stmt1 = $db->prepare("INSERT INTO products_genre (products_id, genre_id) VALUES (?, ?)");
        $stmt1->bind_param("ii", mysqli_insert_id($db), $genreval);
        foreach($genre as $genreval){
            $stmt1->execute();
        }
            $stmt1->close();

    } else {

    }


        
?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>Create new</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group col-sm-9">
            <label>Name</label>
            <input id="name" type="text" name="name" class="form-control">
        </div>
        <div class="form-group col-sm-3">
            <label>Year</label>
            <input id="year" type="text" name="year" class="form-control">
        </div>
        <div class="form-group col-sm-9">
            <label>Description</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>
        <div class="form-group col-sm-9">
            <label>Price</label>
            <input id="price" name="price" type="text" class="form-control">
        </div>
        <div class="form-group col-sm-4">
            <label>Picture</label>
            <input type="file" name="picture" class="form-control">
        </div>
        <div class="form-group col-sm-4">
            <label>Type</label>
            <select name="type" class="form-control">
                <?php
                //select from type table and products table and check if id's match for type, then add as a option
                    $res = $db->query("SELECT * FROM type");
                    while($t = $res->fetch_assoc()){
                ?>
                   <option value="<?= $t['id']?>"
                <?php
                    $res2 = $db->query("SELECT * FROM products");
                    $p = $res2->fetch_assoc();
                        
                    if($t['id'] == $p['type_id']) echo 'selected';
                ?>
                    ><?= $t["type"] ?></option>
                <?php
                    }
                ?>
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label>Genre</label><br>
            <?php 
            //select from genre table and add into loop to display array of genres
                $result = $db->query("SELECT * FROM genre");
                while($g = $result->fetch_assoc()){
            ?>
                <input type="checkbox" name="genre[]" value="<?php echo $g['id']; ?>"><span><?= $g['genre']?></span><br>
            <?php }
            ?>
        </div>
        <div class="form-group col-sm-4">
			<label>Recommend</label>
			<input type="checkbox" name="recommend" <?php if($p['is_recommend']) echo 'checked'; ?> >
		</div>
        <button class="btn btn-success">Save</button>
        <a href="products-read.php" class="btn btn-danger">Cancel</a>
    </form>           
<?php include "footer.php" ?>