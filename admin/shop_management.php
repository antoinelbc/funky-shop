<?php

require_once("../header.php");

/*************************************************************************************************************************************************
        TREATMENT         *******************************************************************************************************************************
**************************************************************************************************************************************************/

/***************************************************** 
 *ACCES RESTRICTED ON ADMIN PAGE 
 *****************************************************/

if(!admin_logged()){
    header("location: ./login.php");
    die();
}

/*****************************************************
 INSERT OR EDIT PRODUCT 
 *****************************************************/

$message = "";

/********* Form Treatment ********/ 

if(isset($_POST['validate']))
{

    //1 - File transfer

    if(isset($_GET['action']) && $_GET['action'] == 'edit-product')
    {
        $image_bdd = $_POST['actual_product_image'];
    }
    
    
    $file = $_FILES['product_image'];

    $file_name = $file['name'];
    $type_mime = $file['type'];
    $file_size = $file['size'];
    $temporary_file = $file['tmp_name'];
    $error_code = $file['error'];

    if(!empty($file_name))
    {
        $image_bdd = ROOT_SITE . "photos/$file_name";
        //$image_bdd = "";

        // The switch below handles different types of errors (related to $_FILES['error'])
        switch ($error_code){
            case UPLOAD_ERR_OK :

            if (is_uploaded_file($temporary_file)) {
                $validate_mime_type = mime_content_type($temporary_file);  
            
                    $allowed_file_types = ['image/png', 'image/jpeg', 'image.jpg'];
                if (! in_array($validate_mime_type, $allowed_file_types)) {
                    $message = "Erreur : fichier de type <strong>" . $type_mime . ".</strong> <br> Veuillez fournir un fichier de type image (<strong>png, jpeg ou jpg</strong>)";
                }
                else{
                    $destination = "C:\wamp64\www\FUNKY-SHOP/photos/$file_name";
                        if (move_uploaded_file($temporary_file, $destination)) {
                            $message = "Transfert terminé - Fichier = $file_name <br>";
                            $message .= "Taille = $file_size octets <br>";
                            $message .= "Type de fichier = $type_mime.";
                        } else {
                            $message = "Le fichier n'a pas pu être copié sur le serveur.";
                        } 
                }
            }   
                break;
            case UPLOAD_ERR_NO_FILE :
                $message = "Aucun fichier n'a été téléchargé.";
                break;
            case UPLOAD_ERR_INI_SIZE : // value of upload_max_filesize in php.ini changed to 3M.
                $message = "Fichier $file_name non transféré";
                $message .= 'Le fichier est trop volumineux (Taille maximale supportée : 3 Mo). ';
                break;
            case UPLOAD_ERR_FORM_SIZE :
                $message = "Fichier $file_name non transféré";
                $message .= 'Le fichier est trop volumineux (Taille maximale supportée : 3 Mo) ';
                break;
            case UPLOAD_ERR_PARTIAL : 
                $message = "Fichier $file_name non transféré";
                $message .= ' Problème lors du transfert. Le fichier n\'a été que partiellement téléchargé.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR :
                $message = "Fichier $file_name non transféré";
                $message .= 'Répertoire temporaire manquant.';
                break;
            case UPLOAD_ERR_CANT_WRITE : 
                $message = "Fichier $file_name non transféré";
                $message .= 'Erreur lors de l\'écriture du fichier sur le disque. ';
                break;
            case UPLOAD_ERR_EXTENSION :
                $message = "Fichier $file_name non transféré";
                $message .= 'Transfert stopppé par une extension PHP. ';
                break;
            default : 
                $message = "Fichier non transféré";
                $mesage .= " (erreur inconnue : $error_code ) . ";
        }

    }

    //2 - Validate Datas

    $reference = data_validator($_POST['reference']);
    $category= data_validator($_POST['category']);
    $product_name = data_validator($_POST['product_name']);
    $product_description = data_validator($_POST['product_description']); 
    $size = data_validator($_POST['size']);
    $color = data_validator($_POST['color']);
    //$image_bdd = data_validator($_POST['product_image']);
    $price = data_validator($_POST['price']);
    $stock = data_validator($_POST['stock']);

    foreach($_POST as $index => $value)
    {
        $_POST[$index] = htmlspecialchars(addSlashes($value));
    }

    //3 - Execute Query

    query_execution("REPLACE INTO products (reference, category, product_name, product_description, size, color, product_image, price, stock) 
    VALUES ('$_POST[reference]', '$_POST[category]', '$_POST[product_name]', '$_POST[product_description]', '$_POST[size]', '$_POST[color]', '$image_bdd', 
    '$_POST[price]', '$_POST[stock]' )");

    header("location: shop_management.php");
    $message = "Le produit a été ajouté !"; 
}

/********* Add product Form (and input complete if edit) ********/ 

if(isset($_GET['action']) && ($_GET['action'] == 'add-product' || $_GET['action'] == 'edit-product'))
{

    if(isset($_GET['id_product']))
    {
        $result = query_execution("SELECT * FROM products WHERE id_product=$_GET[id_product]");
        $actual_product = $result->fetch(PDO::FETCH_ASSOC);
    }

//enctype attribut into the form for upload files
?>
<h1>Gestion de produit</h1>

<div class="product-form-container">
    <form class="product-form" method="POST" enctype="multipart/form-data" action="">
        
        <h2>Produit</h2>
        <input type="hidden" id="id_product" name="id_product" value="<?php if(isset($actual_product['id_product'])){echo $actual_product['id_product'];} ?>">

        <label for="reference">Référence</label>
        <input type="text" id="reference" name="reference" required="required" value="<?php if(isset($actual_product['reference'])){echo $actual_product['reference'];} ?>">
        
        <label for="category">Catégorie</label>
        <input type="text" id="catégory" name="category" required="required" value="<?php if(isset($actual_product['category'])){echo $actual_product['category'];} ?>">

        <label for="product_name">Nom du produit</label>
        <input type="text" id="product_name" name="product_name" required="required" value="<?php if(isset($actual_product['product_name'])){echo $actual_product['product_name'];} ?>">

        <label for="product_description">Description</label>
        <textarea name="product_description" id="description" cols="30" rows="10"><?php if(isset($actual_product['product_description'])){echo $actual_product['product_description'];} ?></textarea>

        <label for="size">Taille</label>
        <select name="size" id="size">
            <option value="none"<?php if(isset($actual_product) && $actual_product['size'] == 'none'){echo 'selected';} ?>>Néant</option>
            <option value="S"<?php if(isset($actual_product) && $actual_product['size'] == 'S'){echo 'selected';} ?>>S</option>
            <option value="M"<?php if(isset($actual_product) && $actual_product['size'] == 'M'){echo 'selected';} ?>>M</option>
            <option value="L"<?php if(isset($actual_product) && $actual_product['size'] == 'L'){echo 'selected';} ?>>L</option>
            <option value="XL"<?php if(isset($actual_product) && $actual_product['size'] == 'XL'){echo 'selected';} ?>>XL</option>
            <option value="XXL"<?php if(isset($actual_product) && $actual_product['size'] == 'XXL'){echo 'selected';} ?>>XXL</option>
        </select>

        <label for="color">Couleur</label>
        <input type="text" id="color" name="color" value="<?php if(isset($actual_product['color'])){echo $actual_product['color'];} ?>">

        <label for="product_image">Image</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
        <input type="file" id="image" name="product_image">
        <?php if(isset($actual_product))
        {
            echo '<strong>Image actuelle : </strong><br>';
            echo '<img src="' . $actual_product['product_image'] . '"  width="100" height="100"><br>';
            echo '<input type="hidden" name="actual_product_image" value="' . $actual_product['product_image'] . '"><br>';
        }
        ?>
        
        <label for="price">Prix</label>
        <input type="text" id="price" name="price" required="required" value="<?php if(isset($actual_product['price'])){echo $actual_product['price'];} ?>">

        <label for="stock">Stock</label>
        <input type="text" id="stock" name="stock" required="required" value="<?php if(isset($actual_product['stock'])){echo $actual_product['stock'];} ?>">

        <div class=submit-btn-container>
            <input type="submit" name="validate" value="Valider">
        </div>
    </form>

    <div class="file-transfert-informations">
        <p><?php echo $message; ?></p>
    </div>

</div>

<?php

}

/*****************************************************
DELETE PRODUCT 
*****************************************************/

if(isset($_GET['action']) && $_GET['action'] == "delete-product"){
    $result = query_execution("SELECT * FROM products WHERE id_product=$_GET[id_product]");
    $product_delete = $result->fetch(PDO::FETCH_ASSOC);
    
    query_execution("DELETE FROM products WHERE id_product=$_GET[id_product]");
    echo "Produit supprimé";
}

/*************************************************************************************************************************************************
        VIEW (SEE PRODUCTS)       *******************************************************************************************************************************
**************************************************************************************************************************************************/


$result = query_execution("SELECT * FROM products");
?>

    <h1>Gestion de la boutique</h1>

    <a class="add-product-btn" href="?action=add-product">Ajouter un produit</a>

    <p class="products-number">Nombre de produits dans la boutique : <?= $result->rowCount(); ?>  </p>
    <table class="products-table">
        <thead>
            <tr>
                <th>Id Produit</th>
                <th>Référence</th>
                <th>Catégorie</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Taille</th>
                <th>Couleur</th>
                <th>Image</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Modification</th>
                <th>Suppression</th>
            <tr>
        </thead>
        <tbody>

        <?php
        $products = $result->fetchAll();
        foreach ($products as $product){
            ?>
            <tr>
                <td><?= $product['id_product']; ?></td>
                <td><?= $product['reference']; ?></td>
                <td><?= $product['category']; ?></td>
                <td><?= $product['product_name']; ?></td>
                <td><?= $product['product_description']; ?></td>
                <td><?= $product['size']; ?></td>
                <td><?= $product['color']; ?></td>
                <td> <img src="<?= $product['product_image']; ?>" height="70" width="70" ></td>
                <td><?= $product['price']; ?> €</td>
                <td><?= $product['stock']; ?></td>
                <td class="action-icon"><a href="?action=edit-product&id_product=<?= $product['id_product'] ?>"><div class="adaptive-img--contain"><span><img src="<?= ROOT_SITE ?>assets/img/icons/edit-icon.svg"></span></div></a></td>
                <td class="action-icon"><a href="?action=delete-product&id_product=<?= $product['id_product'] ?>" onclick="return confirm('Etes-vous sûr de vouloir supprimer ce produit ?');"><div class="adaptive-img--contain"><span><img src="<?= ROOT_SITE ?>assets/img/icons/delete-icon.svg"></span></div></a></td>
            </tr>
            <?php
        }

        ?>
        </tbody>
    </table>


<?php require_once("../footer.php");