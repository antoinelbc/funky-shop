<?php require_once("header.php");

//--------------------->> TREATMENT

//------- Login / logout

if(isset($_GET['action']) && $_GET['action'] == "logout")
{
    session_destroy();
    header("location:login.php"); 
}

//if member logged and get login page, it redirects to his account
if(member_logged()){
    header("location:account.php");
}


//--------- Login form 

if($_POST)
{
    $result = query_execution("SELECT * FROM member WHERE pseudo='$_POST[pseudo]'");
    $member = $result->fetch(PDO::FETCH_ASSOC);

    $pass = $member['pass'];
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

    //check if the password is associate to the pseudo and known in database
    if(password_verify($pass, $pass_hash) == $pass)
    {
        foreach($member as $index => $element){
            if($index != 'pass')
            {
                $_SESSION['member'][$index] = htmlspecialchars($element);       
            }
        }
        header("location:account.php");
    }
    else
    {
    echo '<div class="erreur">Votre identifiant ou votre mot de passe comporte une erreur</div>';
    }
}


//--------------------->> VIEW
?>

<form method="POST" action="">
    <label for="pseudo">Pseudo</label><br>
    <input type="text" id="pseudo" name="pseudo"><br><br>
         
    <label for="pass">Mot de passe</label><br>
    <input type="password" id="pass" name="pass" required="required"><br><br>
 
     <input type="submit" name="login" value="Se connecter">
</form>

<?php require_once("footer.php"); ?>