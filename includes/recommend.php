<?php require_once "init.php" ?>
<div class="row">
    <?php
/* THIS PAGE IS LINKED TO ALL OTHERS WHERE I WANT TO PRESENT RECOMMENDED MOVIES. IT REPRESENTS SCRIPT THAT TAKES US TO PRODUCT INFO BASED ON IT'S ID AND IF IT'S IS_RECOMMEND=1 */
        $result = $db->query('SELECT * FROM products WHERE is_recommend=1 LIMIT 3');
        if($result->num_rows > 0){ //if there are no new movies that we want to recommend (in table "products" is_recommend is 0) it wont write anything
            echo '<h1>Cookie Club recommends</h1><hr>';
        }
        while( $row = $result->fetch_assoc() ){
    ?>
        <a href="products.php?id=<?= $row['id']?>">
            <img src="product_img/<?= $row['picture'] ?>" alt="<?= $row['name'] ?>" class="img-thumbnail w-25">
            <span><?= $row['name'] ?></span>
        </a>
    <?php
        }
    ?>
</div>