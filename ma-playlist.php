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

$playlist_id = (int) filter_input(INPUT_GET, 'playlist_id');

$retour = $bdd->prepare('SELECT p.name AS playlist_name, p.description FROM user u INNER JOIN playlist p ON u.id=p.user_id WHERE u.username=:identifiant AND p.id=:playlist_id');
$retour->bindValue('identifiant', $_SESSION['identifiant'], PDO::PARAM_STR);
$retour->bindValue('playlist_id', $playlist_id, PDO::PARAM_STR);
$retour->execute();
$donnees = $retour->fetch();
$retour->closeCursor();

$retour = $bdd->prepare('SELECT u.username, p.name AS playlist_name, p.description, t.id AS track_id, t.title, t.duration, t.year, ar.name AS artist, al.name AS album, g.name AS genre FROM user u INNER JOIN playlist p ON u.id=p.user_id INNER JOIN playlist_track pt ON pt.playlist_id=p.id INNER JOIN track t ON t.id=pt.track_id INNER JOIN artist ar ON ar.id=t.artist_id INNER JOIN genre g ON g.id=t.genre_id INNER JOIN album al ON al.id=t.album_id WHERE u.username=:identifiant AND p.id=:playlist_id');
//$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
$retour->bindValue('identifiant', $_SESSION['identifiant'], PDO::PARAM_STR);
$retour->bindValue('playlist_id', $playlist_id, PDO::PARAM_STR);
$retour->execute();
$tuples = $retour->fetchAll();
$nb_tuples = count($tuples);
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
                    <h1>Ma playlist</h1>
                </div>
                <?php
                if (isset($erreur)) {
                    ?>

                    <div class = "row" >
                        <div class="alert alert-danger">
                            <?php
                            echo $erreur;
                            ?>
                        </div>
                    </div>

                    <?php
                }
                ?>


                <div class="row">
                    <p>Nom: <?php echo $donnees['playlist_name'] ?></p>
                    <p>Description: <?php echo $donnees['description'] ?></p>

                    <?php
                    if ($nb_tuples > 0) {
                        ?>
                    </div>
                    <div class="row">
                        <ul class="list-group">
    <?php
    foreach ($tuples as $donnees) {
        ?>
                                <li class="list-group-item">

                                    <a href="supprimer-une-musique-de-ma-playlist.php?playlist_id=<?php echo $playlist_id ?>&amp;track_id=<?php echo $donnees['track_id'] ?>" class="btn btn-default pull-right" role="button">
                                        <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> Supprimer de ma playlist
                                    </a>
                                    <span class="titre">
        <?php echo $donnees['title'] ?>
                                    </span>
                                    ( <?php echo $donnees['artist'] ?> )
                                    <br>Album: <?php echo $donnees['album'] ?> - Genre: <?php echo $donnees['genre'] ?> - Année: <?php echo $donnees['year'] ?> - Durée: <?php echo $donnees['duration'] ?> secondes
                                </li>
        <?php
    }
    ?>
                        </ul>
                            <?php
                        } else {
                            ?>

                        <p>Votre playlist ne contient actuellement aucune musique.</p>

    <?php
}
$retour->closeCursor();
?>
                </div>
                <div class="row">
                    <p><a href="ajouter-des-musiques-a-ma-playlist.php?playlist_id=<?php echo $playlist_id ?>">Ajouter des musiques à ma playlist</a><p>
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
