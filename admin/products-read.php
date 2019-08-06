<?php require_once "init.php" ?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>Products</h1>
    <a href="products-create.php" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>New</a>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = $db->query("SELECT * FROM products ORDER BY name ASC");
                while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><a href="products-update.php?id=<?= $row['id']?>">Update</a></td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>

<?php include "footer.php" ?>