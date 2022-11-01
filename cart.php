<?php

require_once("header.php");

/*************************************************************************************************************************************************
        TREATMENT         *******************************************************************************************************************************
**************************************************************************************************************************************************/


/***************************************************** 
 * ADD A PRODUCT TO CART
 *****************************************************/

if(isset($_POST['add-to-cart']))
{
    $result = query_execution("SELECT * FROM products WHERE id_product ='$_POST[id_product]'");
    $product = $result->fetch(PDO::FETCH_ASSOC);
    add_product_to_cart($product['product_name'], $_POST['id_product'], $_POST['quantity'], $product['price']);
}

/***************************************************** 
 * DELETE THE CART
 *****************************************************/

 if(isset($_GET["action"]) && $_GET['action'] == "delete-cart")
 {
    unset($_SESSION['cart']);
 }

 /***************************************************** 
 * PAYMENT
 *****************************************************/

 if(isset($_POST['validate-cart']))
 {
    
    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++)
    {
        $result = query_execution("SELECT * FROM products WHERE id_product =" . $_SESSION['cart']['id_product'][$i]);
        $product = $result->fetch(PDO::FETCH_ASSOC);
        
        if($product['stock'] < $_SESSION['cart']['quantity'][$i])
        {
            echo 'Erreur : Quantité demandée : ' . $_SESSION['cart']['quantity'][$i] . '.<br>';
            echo 'Stock insuffisant. Il ne reste que' . $product['stock'] . 'articles.';
            
            if($product['stock'] > 0)
            {
                echo 'La quantité d\'un produit ' . $_SESSION['cart']['id_product'][$i] . 'a été réduite car notre stock était insuffisant. Veuillez vérifier vos achats à nouveau avant de procéeder au paiement.';
                $_SESSION['cart']['quantity'][$i] = $product['stock'];
            }
            else{
                echo 'Désolé, ce produit est actuellement en rupture de stock. Un produit ' . $_SESSION['cart']['id_product'][$i] . 'a dû être retiré de votre panier. Veuillez vérifier vos achats à nouveau avant de procéder au paiement.';
                delete_product_of_cart($_SESSION['cart']['id_product'][$i]);
                $i--;
            }
            $error = true;
        }

    }

    if(!isset($error))
    {
        query_execution("INSERT INTO orders (id_member, amount, order_date) 
                                        VALUES (" . $_SESSION['member']['id_member'] . "," . total_amount() . ", NOW())"
                                    );
        $id_order = $pdo->lastInsertId();
        
        for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++)
        {
            query_execution("INSERT INTO orders_details (id_order, id_product, quantity, price)
                                VALUES ($id_order, " . $_SESSION['cart']['id_product'][$i] . "," . $_SESSION['cart']['quantity'][$i] . "," . $_SESSION['cart']['price'][$i] . ")"
                            );
        }
        unset($_SESSION['cart']);
        /* Send a mail to the member (not working on localhost)
        mail($_SESSION['member']['email'], 
            "Confirmation de la commande numéro $id_order ", 
            "Bonjour"  . $_SESSION['member']['firstname'] . ". Nous avons le plaisir de vous informer que votre commande $id_order a bien été enregistrée. 
            Nous allons la traiter dans les meilleurs délais. Merci pour votre confiance. ",
            "From: mailsite@mymail.com" );
        */
        echo "Merci pour votre commande. Votre numéro de suivi est le $id_order <br>
        Un mail de confirmation a été envoyé à " . $_SESSION['member']['email'] . "."; 
    }

 }
 



/*************************************************************************************************************************************************
        VIEW        *******************************************************************************************************************************
**************************************************************************************************************************************************/

?>

<h1>Mon Panier</h1>

<table class="cart-table">
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
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
                <td> <a href="?action=delete-products-from-cart">Retirer</a>
                <?php
                    if(isset($_GET["action"]) && $_GET['action'] == "delete-products-from-cart")
                    {
                        $id_product = $_SESSION['cart']['id_product'][$i];
                        delete_product_of_cart($id_product);                    
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td><strong>Total</strong></td>
            <td colspan ="3"> <?= total_amount()?> € </td>
        </tr>
        <tr>
            <td>
                <a href="?action=delete-cart">Vider mon panier</a>
            </td>
            <td colspan="3">
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
            ?>
            </td>
        </tr>
    </tbody>
    <?php
    }
    ?>
</table>

<?php
require_once("footer.php");