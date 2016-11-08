<?php
require('Track.php');
require('Playlist.php');
require('User.php');
require('Playlist-track-user.php');
session_start();

if (!isset($_SESSION['identifiant'])) {
    header('Location: index.php');
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=playlist;charset=utf8', 'root', 'admin', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (isset($_POST['ajouter_une_playlist'])) {
    if (isset($_POST['nom']))
        $nom = htmlspecialchars($_POST['nom']);
    if (isset($_POST['description']))
        $description = htmlspecialchars($_POST['description']);
    if ((!empty($nom)) && (!empty($description))) {

        echo $_FILES['icone']['error'];
//if ($_FILES['icone']['error'] > 0) $erreur = "Erreur lors du transfert";






        /* $test_nom = $bdd->prepare('SELECT COUNT(*) AS nb_noms FROM playlist p INNER JOIN user u ON u.id=p.user_id WHERE u.username=:username AND p.name=:playlistname');
          $test_nom->bindValue('username', $_SESSION['identifiant'], PDO::PARAM_STR);
          $test_nom->bindValue('playlistname', $nom, PDO::PARAM_STR);
          $test_nom->execute();
          $compteur_nb_noms = $test_nom->fetch();
          if ($compteur_nb_noms['nb_noms'] == 0) {
          $selection_id = $bdd->prepare('SELECT id FROM user WHERE username=:username');
          $selection_id->bindValue('username', $_SESSION['identifiant'], PDO::PARAM_STR);
          $selection_id->execute();
          $id=$selection_id->fetchAll();
          $id=$id[0]['id'];

          $ajout = $bdd->prepare('INSERT INTO playlist (name, description, user_id)
          VALUES(:name, :description, :user_id)');
          $ajout->bindValue('name', $nom, PDO::PARAM_STR);
          $ajout->bindValue('description', $description, PDO::PARAM_STR);
          $ajout->bindValue('user_id', $id, PDO::PARAM_STR);
          $ajout->execute();
          header('Location: mes-playlists.php');
          } else {
          $erreur = "Vous avez déjà une playlist nommée ainsi. Veuillez choisir un autre nom s'il vous plaît.";
          } */
    } else {
        $erreur = "Veuillez remplir au minimum les champs \"nom\" et \"description\".";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>Playlist</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link href="css/styles.css" rel="stylesheet">
    </head>
    <body>
        <?php include('entete.php'); ?>
        <main>
            <?php
            if (isset($erreur)) {
                ?>
                <div class = "container">
                    <div class = "row" >
                        <div class="alert alert-danger">
                            <?php
                            echo $erreur;
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class = "container">
                <div class = "row" >
                    <form method = "post" action = "ajouter-une-playlist.php" enctype="multipart/form-data">
                        <label for = "nom" class = "col-xs-6<?php if (isset($erreur) && (($erreur == "Veuillez remplir au minimum les champs \"nom\" et \"description\".") || ($erreur == "Vous avez déjà une playlist nommée ainsi. Veuillez choisir un autre nom s'il vous plaît."))) echo ' erreur'; ?>">Nom:</label>
                        <input type = "text" id = "nom" class = "col-xs-6" name = "nom" placeholder = "Nom de votre playlist"><br>
                        <label for = "description" class = "col-xs-6<?php if ((isset($erreur)) && ($erreur == "Veuillez remplir au minimum les champs \"nom\" et \"description\".")) echo ' erreur'; ?>">Description:</label>
                        <textarea id="description" class = "col-xs-6" name="description" placeholder = "Description"></textarea><br>
                        <label for = "image" class = "col-xs-6" >Image:</label>
                        <input type="file" id = "image" class = "col-xs-6" name = "image"><br>
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                        <input type = "submit" id = "valider" class = pull-right name = "ajouter_une_playlist" value = "Valider"><br>
                    </form>
                </div>
            </div>
        </main>
        <?php include('pied-de-page.php'); ?>
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>
