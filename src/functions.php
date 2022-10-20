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
function upload_image (string $destination, string $input_name, string $submit_name){

    global $message;

    if(isset($_POST[$submit_name]))
    {
        $file_informations = $_FILES[$input_name];
        
        $file_name = $file_informations['name'];
        $type_mime = $file_informations['type'];
        var_dump($type_mime);
        $file_size = $file_informations['size'];
        $temporary_file = $file_informations['tmp_name'];
        $error_code = $file_informations['error'];


        // The switch below handles different types of errors related to $_FILES
        switch ($error_code){
            case UPLOAD_ERR_OK :
                $destination = $destination . '/' . $file_name;
                if (copy($temporary_file, $destination)) {
                    $message = "Transfert terminé - Fichier = $file_name <br>";
                    $message .= "Taille = $file_size octets <br>";
                    $message .= "Type de fichier = $type_mime.";
                }
                else {
                    $message = "Le fichier n'a pas pu être copié sur le serveur.";
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
    
        //The if condition below allows only certain file formats  
        if($type_mime != ['image/png', 'image/jpeg', 'image/jpg']){
            $message = "Format d'image non pris en compte. Veuillez fournir un fichier .jpeg, .jpg ou .png";
        }

    }
}
*/