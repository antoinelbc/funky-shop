<?php

try{
    $user = "root";
    $pass = "";
    $pdo = new PDO( 'mysql:host=localhost;dbname=funky_shop', $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;

}catch(PDOException $e){
    print "Erreur !: " . $e->getMessage();
    die();
}
