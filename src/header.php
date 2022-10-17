<?php 

require_once("init.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funky Shop</title>
    <link href="./assets/styles/style.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="#">Funky Shop</a>
            </div>
            <nav class="primary-menu">
                <ul>
                <?php 
                if(admin_logged()){
                    echo '<li><a href="'. ROOT_SITE . 'admin/member_management.php">Gestion des membres</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'admin/order_management.php">Gestion des commandes</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'admin/shop_management.php">Gestion de la boutique</a></li>';
                }
                if(member_logged()){
                    echo '<li><a href="'. ROOT_SITE . 'account.php">Mon compte</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'shop.php">Boutique</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'cart.php">Mon Panier</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'login.php?action=logout">Se déconnecter</a></li>';
                }
                else{
                    echo '<li><a href="'. ROOT_SITE . 'register.php">S\'inscrire</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'login.php">Se connecter</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'shop.php">Boutique</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'cart.php">Mon Panier</a></li>';
                }
                ?>  
                </ul>
        </nav>
        </div>
    </header>
    <section>
        <div class="global-container">
