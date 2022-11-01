<?php

require_once("../header.php");

/***************************************************** 
 *ACCES RESTRICTED ON ADMIN PAGE 
 *****************************************************/

if(!admin_logged()){
    header("location: ./login.php");
    die();
}





/*************************************************************************************************************************************************
        VIEW (SEE ORDERS)       *******************************************************************************************************************************
**************************************************************************************************************************************************/

$result = query_execution("SELECT * FROM orders INNER JOIN member ON orders.id_member = member.id_member");


?>



<h1>Gestion des commandes</h1>

<h2>Commandes</h2>

<p>Nombre de commandes : <?= $result->rowCount() ?></p>

<table class="orders-table">
        <thead>
            <tr>
                <th>Id Commande</th>
                <th>Membre</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Voir le détail</th>
                <th>Etat</th>
            <tr>
        </thead>

        <?php
        $orders = $result->fetchAll();
        foreach ($orders as $order){
            ?>
            <tr>
                <td><?= $order['id_order']; ?></td>
                <td><?= $order['pseudo']; ?></td>
                <td><?= $order['amount']; ?></td>
                <td><?= date('d-m-Y', strtotime($order['order_date'])); ?></td>
                <td><a href="?action=order-details&id_order=<?= $order['id_order']?>">Voir le détail de la commande</a></td>
                <td><?= $order['order_state']; ?></td>
            </tr>
            <?php
        }

        ?>
        </tbody>
</table>


<h2>Détails de commandes</h2>

<?php

if(isset($_GET['action']) && $_GET['action'] == 'order-details')
{
    $result = query_execution("SELECT * FROM orders_details 
                               INNER JOIN products ON orders_details.id_product = products.id_product
                               WHERE id_order = '$_GET[id_order]'");
    $order_details = $result->fetchAll();
    ?>
    <table class="orders-details-table">
        <thead>
            <tr>
                <th>Id Commande</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix</th>
            <tr>
        </thead>

    <?php
    foreach ($order_details as $order_detail){
        ?>
        
        <tr>
            <td><?= $order_detail['id_order']; ?></td>
            <td class="product-cell"><?= $order_detail['product_name']; ?> <img src="<?= $order_detail['product_image']; ?>" height="70" width="70" ></td>
            <td><?= $order_detail['quantity']; ?></td>
            <td><?= $order_detail['price']; ?></td>
        </tr>
        <?php
    }
    


}



require_once("../footer.php");