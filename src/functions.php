<?php

/*
**** QUERY EXECUTION
*/

function query_execution($query){
    global $pdo;
    try{
        $result = $pdo->prepare($query);
        $result->execute();
        return $result;

    }catch(Exception $e){
        echo "Erreur! " .$e->getMessage();
        die();
    }
};

/*
**** DATA VALIDATOR
*/

function data_validator($datas){
    $datas = trim($datas);
    $datas = stripslashes($datas);
    $datas = htmlspecialchars($datas);
    return $datas;
}

/*
**** LOGIN CHECKER
*/

function member_logged()
{
    if(!isset($_SESSION['member']))
    {
        return false;
    }
    else
    {
        return true;
    }
}

function admin_logged()
{
    if(member_logged() && $_SESSION['member']['status'] == 1)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/*
**** CART
*/

function create_cart()
{
    if(!isset($_SESSION['cart']))
    {
        $_SESSION['cart'] = array();
        $_SESSION['cart']['product_name'] = array();
        $_SESSION['cart']['id_product'] = array();
        $_SESSION['cart']['quantity'] = array();
        $_SESSION['cart']['price'] = array();
    }
}

function add_product_to_cart($product_name, $id_product, $quantity, $price)
{
    create_cart();
    $product_position = array_search($id_product, $_SESSION['cart']['id_product']);
    if($product_position !== false)
    {
        $_SESSION['cart']['quantity'][$product_position] += $quantity;
    }
    else
    {
        $_SESSION['cart']['product_name'][] = $product_name;
        $_SESSION['cart']['id_product'][] = $id_product;
        $_SESSION['cart']['quantity'][] = $quantity;
        $_SESSION['cart']['price'][] = $price;
    }
}

function total_amount() 
{
    $total = 0;
    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++)
    {
        $total +=  $_SESSION['cart']['quantity'][$i] * $_SESSION['cart']['price'][$i];
    }
    return round($total,2);
}

function delete_product_of_cart($id_product_delete)
{
    $product_position = array_search($id_product_to_delete, $_SESSION['cart']['id_product']);
    if($product_position !== false)
    {
        array_splice($_SESSION['cart']['product_name'],$product_position, 1);
        array_splice($_SESSION['cart']['id_product'],$product_position, 1);
        array_splice($_SESSION['cart']['quantity'],$product_position, 1);
        array_splice($_SESSION['cart']['price'],$product_position, 1);
    }
}
