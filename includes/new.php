<?php require_once "init.php" ?>
<div class="row">
    <?php
/* THIS PAGE IS LINKED TO ALL OTHERS WHERE I WANT TO PRESENT NEW MOVIES. IT REPRESENTS SCRIPT THAT TAKES US TO PRODUCT INFO BASED ON IT'S ID*/
        $result = $db->query('SELECT * FROM products ORDER BY year DESC LIMIT 3');
        if($result->num_rows > 0){ //if there are no new movies that we want to emphasize it wont write anything
            echo '<h1>New Movies</h1><hr>';
        }
        while( $row = $result->fetch_assoc() ){
    ?>
        <a href="products.php?id=<?= $row['id']?>">
            <img src="product_img/<?= $row['picture'] ?>" alt="<?= $row['name'] ?>" class="img-thumbnail w-25">
            <span><?= $row['name'] ?></span>
            <span><?= $row['price'] ?></span>
        </a>
    <?php
        }
    ?>
</div>

