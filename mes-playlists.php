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

$retour = $bdd->prepare('SELECT DISTINCT u.username, p.id AS playlist_id, p.name AS playlist_name, p.description FROM user u INNER JOIN playlist p ON u.id=p.user_id WHERE u.username=:identifiant');
//$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
$retour->bindValue('identifiant', $_SESSION['identifiant'], PDO::PARAM_STR);
$retour->execute();
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
            <div class = "container">
                <div class="row">
                    <h1>Mes playlists</h1>
                </div>
            </div>
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
                <div class="row">
                    <?php
                    while ($donnees = $retour->fetch()) {
                        ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">
                                <img src="..." alt="<?php echo $donnees['playlist_name'] ?>">
                                <div class="caption">
                                    <h3><?php echo $donnees['playlist_name'] ?></h3>
                                    <p><?php echo $donnees['description'] ?></p>
                                    <p><a href="ma-playlist.php?playlist_id=<?php echo $donnees['playlist_id'] ?>" class="btn btn-primary" role="button">Accéder</a> <a href="#" class="btn btn-default" role="button">Supprimer</a></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    $retour->closeCursor();
                    ?>
                </div>
            </div>
            <div class = "container">
                <div class="row"><a href="ajouter-une-playlist.php">Ajouter une nouvelle playlist</a></div>
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
