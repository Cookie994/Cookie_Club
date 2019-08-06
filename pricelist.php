<?php require_once "init.php" ?>
<?php include "includes/head.php" ?>
<body>
    <?php include "includes/nav.php" ?>
    <div class="container">
       <div class="row justify-content-center my-4">
           <div class="col-sm-9">
               <h1>Pricelist</h1>
               <form method="get" action="pricelist.php">
                   <label>Sort</label>
                   <select name="sort">
                       <?php
    /*I DON'T SEE THE POINT IN DOING THIS PHP CODE BC EVEN IF I DELET IT IT STILL WORKS FINE. MY PROF DID IT LIKE THIS SO I JUST LEFT IT THERE SO THAT I CAN FIGURE OUT LATER WHAT HE WANTED TO DO HERE*/
                           if(isset($_REQUEST['sort'])){
                                $sort = $_REQUEST['sort'];
                            } else {
                                $sort = 0;
                            }
                        ?>
                        <option <?php if($sort==1) echo "selected"; ?> value="1">Name asc</option>
                        <option <?php if($sort==2) echo "selected"; ?> value="2">Name desc</option>
                        <option <?php if($sort==3) echo "selected"; ?> value="3">Price asc</option>
                        <option <?php if($sort==4) echo "selected"; ?> value="4">Price desc</option>
                   </select>
                   <button class="btn btn-outline-info">Go</button>
               </form>
               <table>
                   <thead>
                       <tr>
                           <th>Product</th>
                           <th>Price($)</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php
                            $sorting = "name asc"; //giving default value
                            if(isset($_REQUEST['sort'])){
                                if($_REQUEST['sort']==1){ //if it's value 1 selected, default value is changing and so on, maybe use switch?
                                    $sorting = "name asc";
                                } else if($_REQUEST['sort']==2){
                                    $sorting = "name desc";
                                } else if($_REQUEST['sort']==3){
                                    $sorting = "price asc";
                                } else if($_REQUEST['sort']==4) {
                                    $sorting = "price desc";
                                }
                            }
                       
                            $result = $db->query("SELECT * FROM products ORDER BY $sorting");
                            while( $row = $result->fetch_assoc() ){
                        ?>
                            <tr>
                                <td>
                                    <a href="products.php?id=<?= $row['id'] ?>">
                                        <?= $row['name'] ?>
                                    </a>
                                </td>
                                <td><?= $row['price'] ?></td>
					        </tr>
                        <?php        
                            }
                       ?>
                   </tbody>
               </table>
           </div>
           <div class="col-sm-3">
               <?php include "includes/recommend.php" ?>
               <?php include "includes/new.php" ?>
           </div>
        </div>

<?php include "includes/footer.php" ?>