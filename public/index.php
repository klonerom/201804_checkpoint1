<?php

require_once __DIR__ . '/../bdd/connexion_pdo.php';
require_once __DIR__ . '/../src/function.php';

//Contact list selection
$query = "SELECT * FROM contact";
$query .= " LEFT JOIN civility ON contact.civility_id = civility.id";
$query .= " ORDER BY contact.lastname ASC";
$contacts = $pdo->query($query);

//variables initialisation
$lastname = '';
$firstname = '';
$civility_id = 0;

if (!empty($_POST)) {

    //init tableau : si un des $_POST non renseigné mettre '' pour éviter une erreur php
    $addContact = [
        'lastname' =>  isset($_POST['lastname']) ? $_POST['lastname'] : '',
        'firstname' => isset($_POST['firstname']) ? $_POST['firstname'] : '',
        'civility_id' => isset($_POST['civility_id']) ? $_POST['civility_id'] : '',
    ];

    $errorLastname = true;
    $errorFirstname = true;
    $errorCivility_id = true;


    //test server error
    if (!empty($addContact['lastname'])) {
        $lastname = $addContact['lastname'];
        $errorLastname = false;
    }

    if (!empty($addContact['firstname'])) {
        $firstname = $addContact['firstname'];
        $errorFirstname = false;
    }

    if ($addContact['civility_id'] != 0) {
        $civility_id = (int) $addContact['civility_id'];
        $errorCivility_id = false;
    }

    //Add contact
    if (!$errorLastname && !$errorFirstname && !$errorCivility_id) {

        $query = "INSERT INTO contact(lastname, firstname, civility_id)";
        $query .= " VALUES (:lastname, :firstname, :civility_id)";

        $insertQuery = $pdo->prepare($query);
        $insertQuery->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $insertQuery->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $insertQuery->bindValue(':civility_id', $civility_id, PDO::PARAM_INT);
        $insertQuery->execute();

        //redirection
        header('Location: index.php?addSuccess=1');
        die;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/checkpoint1.css">

        <!-- FontAwesome -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>

        <title>Checkpoint 1 - Romain Coquery</title>
    </head>
    <body>
        <section>
            <div class="container">
                <div class="row">
                    <h1 class="text-center font-italic">Bienvenue sur le chekpoint1 de Romain C. - Lyon</h1>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <h2><i class="fas fa-list-ul"></i> Liste des contacts</h2>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="table-info">
                                    <th scope="col">Civilité</th>
                                    <th scope="col">NOM Prénom</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($contacts as $contact) {
                                ?>
                                <tr>
                                    <td><?= $contact['civility'] ?></td>
                                    <td><?= fullname($contact['firstname'], $contact['lastname']) ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <h2><i class="far fa-user"></i> Ajouter un contact</h2>
                </div>
                <div class="row messageSuccess">
                    <?php
                    if (!empty($_GET['addSuccess'])) {
                        ?>
                        <i class="far fa-thumbs-up"></i>&nbsp;&nbsp; contact est ajouté !
                        <?php
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="lastname">Nom</label>
                                <input type="text" name="lastname" id="lastname" value="<?= $lastname ?>" class="form-control" placeholder="Saisissez votre nom" required>
                                <?php
                                    if (isset($errorLastname) && $errorLastname) {
                                        ?>
                                        <p class="erreurForm"><i class="fas fa-exclamation"></i>&nbsp;&nbsp;Merci de saisir un nom</p>
                                        <?php
                                    }
                                    ?>
                            </div>
                            <div class="form-group">
                                <label for="firstname">Prénom</label>
                                <input type="text" name="firstname" id="firstname" value="<?= $firstname ?>" class="form-control" placeholder="Saisissez votre prénom" required>
                                <?php
                                if (isset($errorFirstname) && $errorFirstname) {
                                    ?>
                                    <p class="erreurForm"><i class="fas fa-exclamation"></i>&nbsp;&nbsp;Merci de saisir un prénom</p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="civility_id">Civilité</label>
                                <select name="civility_id" class="form-control" required>
                                    <option value="0">Sélectionnez une civilité</option>
                                    <option value="1" <?php if ($civility_id == 1) echo 'selected'?>>M.</option>
                                    <option value="2" <?php if ($civility_id == 2) echo 'selected'?>>Mme.</option>
                                </select>
                                <?php
                                if (isset($errorCivility_id) && $errorCivility_id) {
                                    ?>
                                    <p class="erreurForm"><i class="fas fa-exclamation"></i>&nbsp;&nbsp;Merci de saisir une civilité</p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-info">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>