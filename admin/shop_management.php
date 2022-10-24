<?php

require_once("../header.php");

//--------------------->> TREATMENT

//------- Acces restricted

if(!admin_logged()){
    header("location: ./login.php");
    die();
}

//------- INSERT product

$message = "";


if(isset($_POST['validate']))
{

//1 - File transfer

    $file = $_FILES['product_image'];
    
    $file_name = $file['name'];
    $type_mime = $file['type'];
    $file_size = $file['size'];
    $temporary_file = $file['tmp_name'];
    $error_code = $file['error'];

    $image_bdd = ROOT_SITE . "photos/$file_name";

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

    query_execution("INSERT INTO products (reference, category, product_name, product_description, size, color, product_image, price, stock) 
    VALUES ('$_POST[reference]', '$_POST[category]', '$_POST[product_name]', '$_POST[product_description]', '$_POST[size]', '$_POST[color]', '$image_bdd', 
    '$_POST[price]', '$_POST[stock]' )");

    $message = "Le produit a été ajouté !";
        
}


//------- SEE products

if(isset($_GET['action']) && $_GET['action'] == "products") {

    $result = query_execution("SELECT * FROM products");
?>

    <h2>Produits</h2>
    <p>Nombre de produits dans la boutique : <?= $result->rowCount(); ?>  </p>
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
                <td> <img src="<?= $product['product_image']; ?>" height="70" width="70"></td>
                <td><?= $product['price']; ?> €</td>
                <td><?= $product['stock']; ?></td>
                <td><a href="?action=edit-product&id_product=<?= $product['id_product'] ?>">Modifier</a></td>
                <td><a href="?action=delete-product&id_product=<?= $product['id_product'] ?>" onclick="return confirm('Etes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a></td>
            </tr>
            <?php
        }
        
            
        }
    
    
        ?>
        </tbody>
    </table>

<?php

//------- DELETE product


if(isset($_GET['action']) && $_GET['action'] == "delete-product"){
    $result = query_execution("SELECT * FROM products WHERE id_product=$_GET[id_product]");
    $product_delete = $result->fetch(PDO::FETCH_ASSOC);
    
    query_execution("DELETE FROM products WHERE id_product=$_GET[id_product]");
    echo "Produit supprimé";
}

?>




<?php
//--------------------->> VIEW

//enctype attribut into the form for upload files

?>




<h1>Gestion des produits</h1>

<a href="?action=products">Voir les produits</a>
<a href="?action=add-product">Ajouter un produit</a>



<div class="product-form-container">
    <form class="product-form" method="POST" enctype="multipart/form-data" action="">
        <label for="reference">Référence</label>
        <input type="text" id="reference" name="reference" required="required">
        
        <label for="category">Catégorie</label>
        <input type="text" id="catégory" name="category" required="required">

        <label for="product_name">Nom du produit</label>
        <input type="text" id="product_name" name="product_name" required="required">

        <label for="product_description">Description</label>
        <textarea name="product_description" id="description" cols="30" rows="10"></textarea>

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

        <label for="product_image">Image</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
        <input type="file" id="image" name="product_image">

        <label for="price">Prix</label>
        <input type="text" id="price" name="price" required="required">

        <label for="stock">Stock</label>
        <input type="text" id="stock" name="stock" required="required">

        <div class=submit-btn-container>
            <input type="submit" name="validate" value="Valider">
        </div>
    </form>

    <div class="file-transfert-informations">
        <p><?php echo $message; ?></p>
    </div>

</div>

<?php require_once("../footer.php");