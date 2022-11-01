<?php

require_once("../header.php");

/***************************************************** 
 *ACCES RESTRICTED ON ADMIN PAGE 
 *****************************************************/

if(!admin_logged()){
    header("location: ./login.php");
    die();
}



/*************************************************************************************************************************************************
        VIEW (SEE MEMBERS)       *******************************************************************************************************************************
**************************************************************************************************************************************************/

$result = query_execution("SELECT * FROM member");

?>

<h1>Gestion des membres</h1>

    <p>Membres inscrits sur le site : <?= $result->rowCount(); ?>  </p>
    <table class="members-table">
        <thead>
            <tr>
                <th>Id membre</th>
                <th>Pseudo</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>Statut</th>
            <tr>
        </thead>
        <tbody>
        <?php
        $members = $result->fetchAll();
        foreach ($members as $member){
            ?>
            <tr>
                <td><?= $member['id_member']; ?></td>
                <td><?= $member['pseudo']; ?></td>
                <td><?= $member['firstname']; ?></td>
                <td><?= $member['lastname']; ?></td>
                <td><?= $member['email']; ?></td>
                <td><?= $member['adress']; ?></td>
                <td><?= $member['zip_code']; ?></td>
                <td><?= $member['city']; ?></td>
                <td><span class="member-num-status" style="display:none"><?= $member['status']; ?></span>
                <?php 
                if($member['status'] == 1)
                {
                    echo 'Administrateur';                
                }
                else
                {
                    echo 'Membre';
                }
                ?>
                </td>
            </tr>
            <?php
        }

        ?>
        </tbody>
    </table>



<?php
require_once("../footer.php");