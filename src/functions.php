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