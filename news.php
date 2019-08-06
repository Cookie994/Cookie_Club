<?php require_once "init.php" ?>
<?php include "includes/head.php" ?>
<?php include "includes/nav.php" ?>
    <div class="container">
       <div class="row justify-content-center my-4">
            <div class="col-sm-12">
                <?php
/* THIS PAGE IS LINKED TO INDEX.PHP. IT REPRESENTS SCRIPT THAT TAKES US TO NEWS INFO BASED ON IT'S ID*/
    
                    //linking with index.php - more button
                    $id = $_REQUEST['id']; //looking for id in database - "news.php?id=1"
                    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $row = $res->fetch_assoc();
                ?>
                    <h1><?= $row['title'] ?></h1> <!-- getting info from db based on id requested -->
                    <b><?= $row['date'] ?></b>
                    <img src="product_img/<?= $row['picture'] ?>">
                    <p><?= $row['summary'] ?></p>
                    <div>
                        <?= $row['content'] ?>
                    </div>
           </div>
        </div>
<?php include "includes/footer.php" ?>