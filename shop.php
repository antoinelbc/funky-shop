<?php

require_once("header.php");

$products_categories = query_execution("SELECT DISTINCT category FROM products");

?>
<div class="shop-container">
    <div class="categories-menu-container">
        <ul>
        <?php
        while($category = $products_categories->fetch(PDO::FETCH_ASSOC))
        {
            ?>
                <li>
                    <a href="?category=<?= $category['category']?>"> <?= $category['category'] ?></a>
                </li>
        <?php
        }
        ?>
        </ul>
    </div>

    <div class="categories-products-container">
    <?php
    if(isset($_GET['category']))
    {
        $datas = query_execution("SELECT id_product,reference,product_name,product_image,price FROM products WHERE category='$_GET[category]'");

        while($product = $datas->fetch(PDO::FETCH_ASSOC))
        {
            ?>
            <div class="products-container">
                <h2><?= $product['product_name'] ?></h2>
                <a href="product-sheet.php?id_product=<?=$product['id_product']?>"><img src="<?= $product['product_image'] ?>" alt="photo du produit" width="150" height="150"></a>
                <p><?= $product['price'] ?> €</p>
                <a href="product-sheet.php?id_product=<?=$product['id_product']?>">Voir la fiche produit</a>
            </div>

            <?php
        }

    }
    ?>
    </div>
</div>

<?php
require_once("footer.php");