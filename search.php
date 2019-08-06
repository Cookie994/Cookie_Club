<?php
    require "init.php";
    $s = "";
    $search = "%{$_REQUEST['search']}%";

    $stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ?");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $res = $stmt->get_result();
    while($result = $res->fetch_assoc()){
        $s = $s.
        "<a class='link-srch' href='products.php?id=".$result['id']."'>
            <div class='live-outer'>
                <div class='live-im'>
                    <img src='product_img/".$result['picture']."'/>
                </div>
                <div class='live-product'>
                    <div class='live-product-name'>
                        <p>".$result['name']."</p>
                    </div>
                </div>
            </div>
	   </a>";
    }
    echo $s;
?>