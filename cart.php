<?php

require_once("header.php");

//TREATMENT

if(isset($_POST['add-to-cart']))
{
    $result = query_execution("SELECT * FROM products WHERE id_product ='$_POST[id_product]'");
    $product = $result->fetch(PDO::FETCH_ASSOC);
    add_product_to_cart($product['product_name'], $_POST['id_product'], $_POST['quantity'], $product['price']);
}


//VIEW
?>

<h2>Mon Panier</h2>
<table class="cart-table">
    <tr>
        <th>Produit</th>
        <th>Quantité</th>
        <th>Prix unitaire</th>
        <th>Action</th>
    </tr>
    <?php
    //If cart is empty :
    if(empty($_SESSION['cart']['id_product'])) 
    {
        ?>
        <tr>
            <td colspan="4">Votre panier est vide</td>
        </tr>
        <?php
    }
    else
    {
        for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++)
        {
            ?>
            <tr>
                <td> <?= $_SESSION['cart']['product_name'][$i]?></td>
                <td> <?= $_SESSION['cart']['quantity'][$i]?></td>
                <td> <?= $_SESSION['cart']['price'][$i]?> € </td>
                <td>Retirer</td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td> <strong>Total</strong></td>
            <td colspan ="3"> <?= total_amount()?> € </td>
        </tr>
        <tr>
            <td>
                <a href="?action=delete-cart">Vider mon panier</a>
            </td>
        </tr>
        </table>
        <?php
        if(member_logged())
        {
            ?>
            <form method="POST" action="">
                <input type="submit" name="validate-cart" value="Valider mon panier">
            </form>
            <?php
        }
        else
        {
            ?>
            <p>Veuillez vous <a href="login.php">connecter</a>afin de procéder au paiement </p>
            <p>Pas encore membre ? N'hésitez pas à vous <a href="register.php">inscrire</a></p>
            <?php
        }
}
    


require_once("footer.php");