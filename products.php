<?php require_once "init.php" ?>
<?php include "includes/head.php" ?>
<?php include "includes/nav.php" ?>
<?php
/* THIS PAGE IS LINKED TO CATALOG.PHP. IT REPRESENTS SCRIPT THAT TAKES US TO PRODUCT INFO BASED ON IT'S ID*/
    
    $id = $_REQUEST['id']; //declare $id as a requested id
    $result = $db->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();

    $result = $db->query("SELECT * FROM type WHERE id = {$product['type_id']}"); //select type id's from type table where id matches type_id from products table
    $type = $result->fetch_assoc();
?>
<div class="container">
    <div class="row justify-content-center my-4">
        <div class="col-sm-6">
            <img src="product_img/<?= $product['picture'] ?>"> <!-- from $product take img, name, type... -->
        </div>
        <div class="col-sm-6">
           <h1><?= $product['name'] ?></h1>
           
            <h4>Genre:</h4>
            <?php
                $result = $db->query("SELECT * FROM products_genre
                                    INNER JOIN genre ON genre.id = products_genre.genre_id
                                    WHERE products_genre.products_id = {$product['id']}");
                 /*select all from junction table to match id from genre table with genre_id from junction table if product id is equal to product_id from junction table*/
                while( $genre = $result->fetch_assoc() ){ //place it in while so that it can list all genres for one movie!
            ?>
                <i><?= $genre['genre'] ?></i><br>
            <?php
                }
            ?>

           <h4>Type:</h4>
           <p><?= $type['type'] ?></p><br>

           <h4>Year:</h4>
           <p><?= $product['year'] ?></p><br>
           
           <h4>Price($):</h4>
           <p><?= $product['price'] ?></p><br>

           <h4>Description:</h4>
           <p><?= $product['description'] ?></p><br>
        </div>
    </div>
<?php include "includes/footer.php" ?>
