<?php
require('Track.php');
require('Playlist.php');
require('User.php');
require('Playlist-track-user.php');
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=playlist;charset=utf8', 'root', 'admin', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (isset($_POST['valider_ajout']) || isset($_POST['modifier'])) {
    if (isset($_POST['title']))
        $title = htmlspecialchars($_POST['title']);
    if (isset($_POST['duration']))
        $duration = (int) htmlspecialchars($_POST['duration']);
    if (isset($_POST['year']))
        $year = (int) htmlspecialchars($_POST['year']);
    if (isset($_POST['artist']))
        $artist = htmlspecialchars($_POST['artist']);


    if ((!empty($title)) && (!empty($duration)) && (!empty($year)) && (!empty($artist))) {
        if (isset($_POST['valider'])) {
            $insertion = $bdd->prepare('INSERT INTO track (title, duration, year, artist)
          VALUES(:title, :duration, :year, :artist)');
            $insertion->bindValue('title', $title, PDO::PARAM_STR);
            $insertion->bindValue('duration', $duration, PDO::PARAM_INT);
            $insertion->bindValue('year', $year, PDO::PARAM_INT);
            $insertion->bindValue('artist', $artist, PDO::PARAM_STR);
            $insertion->execute();
        }
        if (isset($_POST['modifier'])) {
            $id = (int) htmlspecialchars($_POST['id']);
            $modification = $bdd->prepare('UPDATE track SET title=:title, duration=:duration , year=:year , artist=:artist WHERE id=:id');
            $modification->bindValue('id', $id, PDO::PARAM_INT);
            $modification->bindValue('title', $title, PDO::PARAM_STR);
            $modification->bindValue('duration', $duration, PDO::PARAM_INT);
            $modification->bindValue('year', $year, PDO::PARAM_INT);
            $modification->bindValue('artist', $artist, PDO::PARAM_STR);
            $modification->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
            $modification->execute();
        }
    } else {
        $erreur = "Veuillez remplir tous les champs s'il vous plaît.";
    }
}
$stmt = $bdd->query('SELECT * FROM track');
$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
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
                    <form method = "post" action = "index.php">
                        <label for="title" class = "col-xs-6">Titre:</label>
                        <input type = "text" id = "title" class = "col-xs-6" name = "title" placeholder = "Titre de la chanson"><br>
                        <label for="duration" class = "col-xs-6">Durée:</label>
                        <input type = "number" id = "duration" class = "col-xs-6" name = "duration" placeholder = "Durée en secondes"><br>
                        <label for="year" class = "col-xs-6">Année:</label>
                        <input type = "number" id = "year" class = "col-xs-6" name = "year" placeholder = "Année"><br>
                        <label for="artist" class = "col-xs-6">Artist:</label>
                        <input type = "text" id = "artist" class = "col-xs-6" name = "artist" placeholder = "Artiste ou groupe"><br>
                        <input type = "submit" id = "valider" class = pull-right name = "valider_ajout" value = "Valider"><br>
                    </form>
                </div>
            </div>
            <div class="container">
                <?php
                while ($track = $stmt->fetch()) {
                    echo '<ul class="row">';
                    echo '<li>' . $track->getTitle() . '</li>';
                    echo '<li>' . $track->getDuration() . '</li>';
                    echo '<li>' . $track->getYear() . '</li>';
                    echo '<li>' . $track->getArtist() . '</li>';
                    echo '<li><a href="modifier.php?id=' . $track->getId() . '">Modifier</a></li>';
                    echo '<li><a href="supprimer.php?id=' . $track->getId() . '">Supprimer</a></li>';
                    echo '</ul>';
                }
                ?>
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
