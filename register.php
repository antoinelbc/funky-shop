<?php require_once("./src/header.php"); 

//--------------------->> TREATMENT

if(isset($_POST['register']))
{
    $pseudo = data_validator($_POST['pseudo']); 
    $lastname= data_validator($_POST['lastname']);
    $firstname = data_validator($_POST['firstname']);
    $email = data_validator($_POST['email']);
    $adress = data_validator($_POST['adress']);
    $city = data_validator($_POST['city']);
    $zip_code = data_validator($_POST['zip_code']);
    $pass = data_validator($_POST['pass']);

    $check_entries = preg_match('#^[a-zA-Z0-9._-âàéèêîïôö]+$#', $_POST['pseudo']); 
    
    if(!$check_entries && (strlen($_POST['pseudo']) < 1 || strlen($_POST['pseudo']) > 20) ) // 
    {
        echo "<div class='erreur'>Le pseudo doit contenir entre 1 et 20 caractères. <br> Caractères accepté : Lettre de A à Z et chiffre de 0 à 9</div>";
    }
    else
    {
        $member_pseudo = query_execution("SELECT * FROM member WHERE pseudo='$_POST[pseudo]'");
        
        if($member_pseudo->fetchColumn() > 0)
        {
            echo "<div class='erreur'>Pseudo indisponible. Veuillez en choisir un autre svp.</div>";
        }
        else
        {
            $pass_hash = password_hash('pass', PASSWORD_DEFAULT);
            
            $_POST['pass'] = $pass_hash;
            foreach($_POST as $index => $value)
            {
                $_POST[$index] = htmlspecialchars(addSlashes($value));
            }
            
            query_execution("INSERT INTO member (pseudo, pass, lastname, firstname, email, adress, city, zip_code) VALUES ('$_POST[pseudo]', '$_POST[pass]', '$_POST[lastname]', '$_POST[firstname]', '$_POST[email]', '$_POST[adress]', '$_POST[city]', '$_POST[zip_code]')");
            echo "<div class='validation'>Votre inscription a bien été prise en compte. <a href=\"connexion.php\">Cliquez ici pour vous connecter</a></div>";
        }
    }
}

//--------------------->> VIEW
?>

<form method="POST" action="">
    <label for="pseudo">Pseudo</label><br>
    <input type="text" id="pseudo" name="pseudo" maxlength="20" pattern="[a-zA-Z0-9-_.âàéèêîïôö]{1,20}" title="Caractères acceptés : a-zA-Z0-9-_." required="required"><br><br>
          
    <label for="pass">Mot de passe</label><br>
    <input type="password" id="pass" name="pass" maxlength="32" required="required"><br><br>
          
    <label for="lastname">Nom</label><br>
    <input type="text" id="lastname" name="lastname" maxlength="32" pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-\s*+$/u{1,32}" required="required"><br><br>
          
    <label for="firstname">Prénom</label><br>
    <input type="text" id="firstname" name="firstname" maxlength="32" pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-\s]+$/u{1,32}" required="required"><br><br>
  
    <label for="email">Email</label><br>
    <input type="email" id="email" name="email" placeholder="exemple@gmail.com" maxlength="50" pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})" required="required"><br><br>
    
    <label for="adress">Adresse</label><br>
    <textarea id="adress" name="adress" maxlength="50" pattern="[a-zA-Z0-9-_.]{5,50}" title="caractères acceptés : a-zA-Z0-9-_." required="required"></textarea><br><br>

    <label for="city">Ville</label><br>
    <input type="text" id="city" name="city" maxlength="32" pattern="[a-zA-Z-]{5,32}" title="caractères acceptés : a-zA-Z-" required="required"><br><br>
          
    <label for="zip_code">Code Postal</label><br>
    <input type="text" id="zip_code" name="zip_code" pattern="[0-9]{5}" title="5 chiffres requis : 0-9"><br><br>
 
    <input type="submit" name="register" value="S'inscrire">
</form>


<?php require_once("./src/footer.php"); ?>