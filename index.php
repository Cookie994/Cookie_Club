<?php require_once "init.php" ?>
<?php include "includes/head.php" ?>
<body>
    <?php include "includes/nav.php" ?>
    <div class="container">
       <div class="row justify-content-center my-4">
            <div class="col-sm-12 text-center">
               <img src="img/head-img.jpeg" alt="camera" class="img-fluid">
            </div>
       </div>
       <div class="row my-4 pt-4">
           <div class="col-sm-9">
               <h1>News</h1>
               <hr>
                <?php
    /* LISTING ALL NEWS - NEWS.PHP */
                   $result = $db->query('SELECT * FROM news ORDER BY date DESC'); //put db result in a new variable
                    while( $row = $result->fetch_assoc() ) { //bc we have more rows use fetch_assoc (to move to the next row) in while, $row now containes the sql query and data
                ?>
                        <article>
                            <h3><?= $row['title'] ?></h3> <!-- from $row we want data under "title" and                                     "summary" -->
                            <p> <?= $row['summary'] ?></p>
                            <a class="btn btn-outline-info" href="news.php?id=<?= $row['id'] ?>">More</a>
                       </article>
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