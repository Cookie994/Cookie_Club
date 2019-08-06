<?php require_once "init.php" ?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>Genres</h1>
    <a href="genre-create.php" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>New</a>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = $db->query("SELECT * FROM genre ORDER BY genre ASC");
                while($gen = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?= $gen['genre'] ?></td>
                    <td><a href="genre-update.php?id=<?= $gen['id']?>">Update</a></td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>

<?php include "footer.php" ?>