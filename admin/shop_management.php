<?php

require_once("../header.php");

//--------------------->> TREATMENT

//------- Acces restricted

if(!admin_logged()){
    header("location: ./login.php");
    die();
}

//------- Insert product


//ICI LE DATA VALIDATOR

//TRANSFERT DE FICHIER

$message = "";

if(isset($_POST['validate']))
{
    $file_informations = $_FILES['image'];
    
    $file_name = $file_informations['name'];
    $type_mime = $file_informations['type'];
    $file_size = $file_informations['size'];
    $temporary_file = $file_informations['tmp_name'];
    $error_code = $file_informations['error'];

    switch ($error_code){
        case UPLOAD_ERR_OK :
            $destination = "C:\wamp64\www\FUNKY-SHOP/photos/$file_name";
            if (copy($temporary_file, $destination)) {
                $message = "Trasnfert terminé - Fichier = $file_name <br>";
                $message .= "Taille = $file_size octets <br>";
                $message .= "Type de fichier = $type_mime.";
            } else {
                $message = "Le fichier n'a pas pu être copié sur le serveur.";
            }
            break;
        case UPLOAD_ERR_NO_FILE :
            $message = "Aucun fichier saisi.";
            break;
        case UPLOAD_ERR_INI_SIZE :
            $message = "Fichier $file_name non transféré";
            $message .= ' (taille > upload_max_filesize) . ';
            break;
        case UPLOAD_ERR_FORM_SIZE :
            $message = "Fichier $file_name non transféré";
            $message .= ' (taille > MAX_FILE_SIZE) . ';
            break;
        case UPLOAD_ERR_PARTIAL : 
            $message = "Fichier $file_name non transféré";
            $message .= ' (Problème lors du transfert) . ';
            break;
        case UPLOAD_ERR_NO_TMP_DIR :
            $message = "Fichier $file_name non transféré";
            $message .= ' (pas de répertoire temporaire) . ';
            break;
        case UPLOAD_ERR_CANT_WRITE : 
            $message = "Fichier $file_name non transféré";
            $message .= ' (erreur lors de l\'écriture du fichier sur le disque) . ';
            break;
        case UPLOAD_ERR_EXTENSION :
            $message = "Fichier $file_name non transféré";
            $message .= ' (transfert stopppé par l\'extension) . ';
            break;
        default : 
            $message = "Fichier non transféré";
            $mesage .= " (erreur inconnue : $error_code ) . ";
    }
}



//--------------------->> VIEW
//enctype attribut into the form for upload files
?>

<h1>Gestion des produits</h1>

<div class="product-form-container">
    <form class="product-form" method="POST" enctype="multipart/form-data" action="">
        <label for="reference">Référence</label>
        <input type="text" id="reference" name="reference">
        
        <label for="category">Catégorie</label>
        <input type="text" id="catégory" name="category">

        <label for="product_name">Nom du produit</label>
        <input type="text" id="product_name" name="product_name">

        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>

        <label for="size">Taille</label>
        <select name="size" id="size">
            <option value="none">Néant</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">XXL</option>
        </select>

        <label for="color">Couleur</label>
        <input type="text" id="color" name="color">

        <label for="image">Image</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
        <input type="file" id="image" name="image">

        <label for="price">Prix</label>
        <input type="text" id="price" name="price">

        <label for="stock">Stock</label>
        <input type="text" id="stock" name="stock">

        <div class=submit-btn-container>
            <input type="submit" name="validate" value="Valider">
        </div>
    </form>

    <div class="file-transfert-informations">
        <p><?php echo $message; ?></p>
    </div>

</div>

<?php require_once("../footer.php");