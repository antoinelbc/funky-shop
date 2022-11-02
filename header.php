<?php 

require_once("src/init.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funky Shop</title>
    <link href="./assets/styles/style.css" rel="stylesheet">
    <link href="../assets/styles/style.css" rel="stylesheet">


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
                    echo '<li><a href="'. ROOT_SITE . 'shop.php">Boutique</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'account.php">Mon compte<div class="adaptive-img--contain"><span><img src="' . ROOT_SITE .'assets/img/icons/account-icon.svg"></span></div></a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'cart.php">Mon Panier<div class="adaptive-img--contain"><span><img src="' . ROOT_SITE .'assets/img/icons/cart-icon.svg"></span></div></a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'login.php?action=logout">Se déconnecter<div class="adaptive-img--contain"><span><img src="' . ROOT_SITE .'assets/img/icons/logout-icon.svg"></span></div></a></li>';
                }
                else{
                    echo '<li><a href="'. ROOT_SITE . 'register.php">S\'inscrire</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'login.php">Se connecter<div class="adaptive-img--contain"><span><img src="' . ROOT_SITE .'assets/img/icons/login-icon.svg"></span></div></a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'shop.php">Boutique</a></li>';
                    echo '<li><a href="'. ROOT_SITE . 'cart.php">Mon Panier</a></li>';
                }
                ?>  
                </ul>
        </nav>
        </div>
    </header>
    <section class="page-container">
        <div class="global-container">
