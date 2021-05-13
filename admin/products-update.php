<?php require_once "init.php" ?>
<?php
    $id = intval($_REQUEST['id']);/*because we already have the news with it's id in the base, there is no need
    to generate a new name for a picture (it can be named like a id from the table)*/
    //checking to see if the product was send
    if(isset($_REQUEST['name'])){
        $name = trim(filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING));
        $year = trim(filter_var($_REQUEST['year'], FILTER_SANITIZE_STRING));
        $des = trim(filter_var($_REQUEST['description'], FILTER_SANITIZE_STRING));
        $price = trim(filter_var($_REQUEST['price'], FILTER_SANITIZE_STRING));
        $type = $_REQUEST['type'];
        
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
                $tmpName = "products-$id.$ext"; //name of the picture
                $destination = "$dir/$tmpName";
                move_uploaded_file($_FILES['picture']['tmp_name'], $destination);
            }

        }
        //update the table
        $stmt = $db->prepare("UPDATE products SET name = ?, year = ?, description = ?, price = ?, picture = ?, is_recommend = ?, type_id = ?
                            WHERE id = ?");
        $stmt->bind_param("sssssssi", $name, $year, $des, $price, $tmpName, $recommend, $type, $id);
        $stmt->execute();
        $stmt->close();


    }
        
    /*because products_genre is a junction table I couldn't do a simple UPDATE query because it will lead to duplicate error in the primary column. So I did a delete and insert querys instead*/
   if (!empty($_POST["genre"]) ) {
        $genre = $_POST["genre"];
        $stmt1 = $db->prepare("DELETE FROM products_genre WHERE products_id = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();
       
        $stmt1 = $db->prepare("INSERT INTO products_genre (genre_id, products_id) VALUES (?, ?)");
        $stmt1->bind_param("ii", $genreval, $id);
        foreach($genre as $genreval){
            $stmt1->execute();
            
        }
            $stmt1->close();

    } else {

    }

    //to load a specific product trough id, default values in the form
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();

        
?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>Update product <?= $product['name'] ?></h1><!--Trough id we get a title of the product we want to change-->
    <form method="post" action="products-update.php?id=<?= $id ?>" enctype="multipart/form-data">
       <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group col-sm-9">
            <label>Name</label>
            <input id="name" type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name'])?>">
        </div>
        <div class="form-group col-sm-3">
            <label>Year</label>
            <input id="year" type="text" name="year" class="form-control" value="<?= htmlspecialchars($product['year'])?>">
        </div>
        <div class="form-group col-sm-9">
            <label>Description</label>
            <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($product['description'])?></textarea>
        </div>
        <div class="form-group col-sm-9">
            <label>Price</label>
            <input id="price" name="price" type="text" class="form-control" value="<?= htmlspecialchars($product['price'])?>">
        </div>
        <div class="form-group col-sm-4">
            <label>Picture</label>
            <input type="file" name="picture" class="form-control">
        </div>
        <div class="form-group col-sm-4">
            <?php if($product['picture']){?>
                    <img src="../product_img/<?= $product['picture']?>">
                    <?php } else{?>
                        No picture
                    <?php } ?>
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
                    if($t['id'] == $product['type_id']) echo 'selected';
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
                //to load checkboxes from table
                $res2 = $db->query("SELECT genre_id FROM products_genre WHERE products_id = $id");
            ?>
                <input type="checkbox" name="genre[]" value="<?php echo $g['id']; ?>"
                    <?php
                        while($prod_gen = $res2->fetch_assoc()){//put in while to load all checked boxes
                            if(in_array($g['id'], $prod_gen))echo "checked";//check if it's in the db
                        }?> >
                <span><?= $g['genre']?></span><br>
            <?php 
                }
            ?>
        </div>
        <div class="form-group col-sm-4">
			<label>Recommend</label>
			<input type="checkbox" name="recommend" <?php if($product['is_recommend']) echo 'checked'; ?> >
		</div>
        <button type="submit" value="Submit" class="btn btn-success">Save</button>
        <a href="products-read.php" class="btn btn-secondary">Cancel</a>
        <!-- Only superadmin can delete-->
        <?php if($_SESSION['user']['accesslevel'] >= 10){ ?>
            <button type="button" class="btn btn-danger" onclick=" if(confirm('Delete product?')){ window.location.assign('products-delete.php?id=<?= $id ?>'); } ">Delete</button>
        <?php } ?>
    </form>           
<?php include "footer.php" ?>
