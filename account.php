<?php require_once("./src/header.php"); 

//--------------------->> TREATMENT

if(!member_logged())
{
    header("location:connexion.php");
}


$informations = '';
$informations .= '<div class="account-container"><p>Bonjour <strong>' . $_SESSION['member']['pseudo'] . '</strong>,</p><br>';
$informations .= '<div class="account-informations"><h2> Voici vos informations </h2><br>';
$informations .= '<p> Votre email est: ' . $_SESSION['member']['email'] . '</p><br>';
$informations .= 'Votre adresse est: ' . $_SESSION['member']['adress'] . '</p>';
$informations .= 'Votre code postal est: ' . $_SESSION['member']['zip_code'] . '</p>';
$informations .= 'Votre ville est: ' . $_SESSION['member']['city'] . '</p><br></div></div>';


//--------------------->> VIEW

echo $informations;
require_once("./src/footer.php");

?>