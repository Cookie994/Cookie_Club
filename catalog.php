<?php require_once "init.php" ?>
<?php include "includes/head.php" ?>
<body>
    <?php include "includes/nav.php" ?>
    <div class="container">
       <div class="row justify-content-center my-4">
            <div class="col-sm-9">
               <h1>Cookie Club's movies</h1> 
            </div>
        </div>
        <div class="row">
           <div class="col-sm-9">
                <?php
                    /* LISTING ALL PRODUCTS - PRODUCTS.PHP */
                    $result = $db->query("SELECT * FROM products ORDER BY name DESC");
                    while( $row = $result->fetch_assoc() ){ //placed in while so that it can list them all
                ?>
                       <div class="col-sm-4">
                            <a href="products.php?id=<?= $row['id']?>">
                                <img src="product_img/<?= $row['picture'] ?>" alt="<?= $row['name'] ?>" class="img-thumbnail">
                            </a>
                            <div>
                                <h4><?= $row['name'] ?></h4>
                                <p><?= $row['price'] ?></p>
                            </div>
                        </div>
                <?php
                    }
                ?>
                </div>
                <div class="col-sm-3">
                    <?php include "includes/recommend.php" ?>
                    <?php include "includes/new.php" ?>
               </div>
        </div>
<?php include "includes/footer.php" ?>