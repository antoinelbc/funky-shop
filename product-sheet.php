<?php

require_once("header.php");

if(isset($_GET['id_product'])) 
{
    $result = query_execution("SELECT * FROM products WHERE id_product = '$_GET[id_product]'");

    if($result->rowCount() <= 0) 
    {
    header("location:shop.php");
    die();
    }
}


$product = $result->fetch(PDO::FETCH_ASSOC);



?>
<div class="product-sheet-container">
    <div class="product-sheet-image_container">
        <div class="adaptive-img--contain">
            <span>
                <img src="<?= $product['product_image'] ?>">
            </span>
        </div>
    </div>
    <div class="product-sheet-desc-container">
        <h2><?= $product['product_name']?></h2>
        <div class="features">
            <p>Catégorie : <?= $product['category']?></p>
            <p>Couleur : <?= $product['color']?></p>
            <p>Taille : <?= $product['size']?></p>
        </div>
        <div class="description">
            <p>Description : <?= $product['product_description']?></p>
        </div>
        <div class="price-and-stock">
        <p>Prix : <?=$product['price']?> €</p>

<?php

if($product['stock'] > 0)
{
    ?>
    <p>En stock : <?= $product['stock']?></p>
    <form method="POST" action="cart.php">
        <input type="hidden" name="id_product" value="<?= $product['id_product'] ?>">
        <label for="quantity">Quantité : </label>
        <select name="quantity" id="quantity">
            <?php
            for($i = 1; $i <= $product['stock'] && $i <= 10; $i++)
            {
                ?>
                <option value="<?= $i ?>"><?= $i ?></option>
                <?php
            }
            ?>
        </select>
        <input type="submit" name="add-to-cart" value="Ajouter au panier">
    </form>
<?php
}
else{
    echo "Rupture de stock";
}
?>
        </div>
    </div>
</div>
<a href="shop.php?category=<?= $product['category']?>">Retour vers la boutique <?= $product['category'] ?></a>

<?php
require_once("footer.php");