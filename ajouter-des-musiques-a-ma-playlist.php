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
$track_id = (int) filter_input(INPUT_GET, 'track_id');

$retour = $bdd->prepare('SELECT COUNT(t.id) FROM track t INNER JOIN artist ar ON ar.id=t.artist_id INNER JOIN genre g ON g.id=t.genre_id INNER JOIN album al ON al.id=t.album_id WHERE t.id NOT IN (SELECT pt.track_id FROM playlist_track pt WHERE pt.playlist_id=:playlist_id)');
$retour->bindValue('playlist_id', $playlist_id, PDO::PARAM_INT);
$retour->execute();
$nb_tuples = $retour->fetchColumn();
$retour->closeCursor();
    
$retour = $bdd->prepare('SELECT t.id, t.title, t.duration, t.year, ar.name AS artist, g.name AS genre, al.name AS album FROM track t INNER JOIN artist ar ON ar.id=t.artist_id INNER JOIN genre g ON g.id=t.genre_id INNER JOIN album al ON al.id=t.album_id WHERE t.id NOT IN (SELECT pt.track_id FROM playlist_track pt WHERE pt.playlist_id=:playlist_id)');
//$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
$retour->bindValue('playlist_id', $playlist_id, PDO::PARAM_INT);
$retour->execute();


if ((!empty($playlist_id)) && (!empty($track_id))) {
    $retour = $bdd->prepare('INSERT INTO playlist_track (playlist_id, track_id) VALUES (:playlist_id, :track_id)');
    $retour->bindValue('playlist_id', $playlist_id, PDO::PARAM_INT);
    $retour->bindValue('track_id', $track_id, PDO::PARAM_INT);
    $retour->execute();
    $retour->closeCursor();
    header('Location: ajouter-des-musiques-a-ma-playlist.php?playlist_id=' . $playlist_id);
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
            <div class = "container">
                <div class="row">
                    <h1>Ajouter des musiques à ma playlist</h1>
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
                <div class = "row" >

                    <?php
                    if ($nb_tuples > 0) {
                        ?><ul class="list-group"><?php
                        while ($donnees = $retour->fetch()) {
                            ?>
                                <li class="list-group-item">

                                    <a href="ajouter-des-musiques-a-ma-playlist.php?playlist_id=<?php echo $playlist_id ?>&amp;track_id=<?php echo $donnees['id'] ?>" class="btn btn-default pull-right" role="button">
                                        <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Ajouter à ma playlist
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
                        </ul><?php
                        } else {
                            ?>
                        <p>Vous avez déjà toutes les musiques dans votre playlist</p>
                        <?php
                        }
                        $retour->closeCursor();
                        ?>

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
