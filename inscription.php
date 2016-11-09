<?php
require('Track.php');
require('Playlist.php');
require('User.php');
require('Playlist-track-user.php');
session_start();

if (isset($_SESSION['identifiant'])) {
    $_SESSION = array();
    session_destroy();
    header('Location: inscription.php');
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=playlist;charset=utf8', 'root', 'admin', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (isset($_POST['valider_inscription'])) {
    if (isset($_POST['username']))
        $identifiant = htmlspecialchars($_POST['username']);
    if (isset($_POST['password']))
        $mdp = htmlspecialchars($_POST['password']);
    if (isset($_POST['email']))
        $email = htmlspecialchars($_POST['email']);
    if ((!empty($identifiant)) && (!empty($mdp)) && (!empty($email))) {
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);
        $test_identifiant = $bdd->prepare('SELECT COUNT(*) AS nb_identifiants FROM user WHERE username=:username');
        $test_identifiant->bindValue('username', $identifiant, PDO::PARAM_STR);
        $test_identifiant->execute();
        $compteur_nb_identifiants = $test_identifiant->fetch();
        if ($compteur_nb_identifiants['nb_identifiants'] == 0) {
            $insertion = $bdd->prepare('INSERT INTO user (username, password, email)
          VALUES(:username, :password, :email)');
            $insertion->bindValue('username', $identifiant, PDO::PARAM_STR);
            $insertion->bindValue('password', $mdp, PDO::PARAM_STR);
            $insertion->bindValue('email', $email, PDO::PARAM_STR);
            $insertion->execute();
            header('Location: index.php');
        } else {
            $erreur = "Cet identifiant est déjà pris par un autre utilisateur. Veuillez en choisir un autre s'il vous plaît.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs s'il vous plaît.";
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
                    <form method = "post" action = "inscription.php">
                        <label for = "username" class = "col-xs-6<?php if (isset($erreur) && (($erreur == "Veuillez remplir tous les champs s'il vous plaît.") || ($erreur == "Cet identifiant est déjà pris par un autre utilisateur. Veuillez en choisir un autre s'il vous plaît."))) echo ' erreur'; ?>">Identifiant:</label>
                        <input type = "text" id = "username" class = "col-xs-6" name = "username" placeholder = "Identifiant"><br>
                        <label for = "password" class = "col-xs-6<?php if ((isset($erreur)) && ($erreur == "Veuillez remplir tous les champs s'il vous plaît.")) echo ' erreur'; ?>">Mot de passe:</label>
                        <input type = "password" id = "password" class = "col-xs-6" name = "password" placeholder = "Mot de passe"><br>
                        <label for = "email" class = "col-xs-6<?php if ((isset($erreur)) && ($erreur == "Veuillez remplir tous les champs s'il vous plaît.")) echo ' erreur'; ?>">Email:</label>
                        <input type = "email" id = "email" class = "col-xs-6" name = "email" placeholder = "Email"><br>
                        <input type = "submit" id = "valider" class = pull-right name = "valider_inscription" value = "Valider"><br>
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
