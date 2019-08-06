<?php require_once "init.php" ?>
<?php include "head.php" ?>
<?php include "nav.php" ?>
<body>
    <h1>News</h1>
    <a href="news-create.php" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>New</a>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = $db->query("SELECT * FROM news ORDER BY date DESC");
                while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><a href="news-update.php?id=<?= $row['id']?>">Update</a></td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>

<?php include "footer.php" ?>