<?php
require('Track.php');
if (isset($_GET['id']))
  $id = (int)$_GET['id'];

try
{
  $bdd = new PDO('mysql:host=localhost;dbname=playlist;charset=utf8', 'root', 'admin', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}

if (isset($id))
{
  $stmt = $bdd->prepare('SELECT * FROM track WHERE id=:id');
  $stmt->bindValue('id', $id);
  $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
  $stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <meta name="description" content="">
      <link rel="icon" href="../../favicon.ico">

      <title>Playlist</title>
      <!-- Bootstrap core CSS -->
      <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

      <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
      <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

      <!-- Custom styles for this template -->
      <link href="css/offcanvas.css" rel="stylesheet">

      <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
      <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
      <script src="js/ie-emulation-modes-warning.js"></script>

      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>
    <body>
      <div class="container">
        <div class="row" >
    <form method="post" action="musique.php">
      <?php

      while ($track = $stmt->fetch())
      {
        ?>
      <label for "title" class="col-xs-6 col-lg-6">Title:</label>
      <input type="text" id="title" class="col-xs-6 col-lg-6" name="title" value="<?php echo $track->getTitle(); ?>"><br>
      <label for "duration" class="col-xs-6 col-lg-6">Duration:</label>
      <input type="number" id="duration" class="col-xs-6 col-lg-6" name="duration" value="<?php echo $track->getDuration(); ?>"><br>
      <label for "year" class="col-xs-6 col-lg-6">Year:</label>
      <input type="number" id="year" class="col-xs-6 col-lg-6" name="year" value="<?php echo $track->getYear(); ?>"><br>
      <label for "artist" class="col-xs-6 col-lg-6">Artist:</label>
      <input type="text" id="artist" class="col-xs-6 col-lg-6" name="artist" value="<?php echo $track->getArtist(); ?>"><br>
      <input type="hidden" name="id" value="<?php echo $track->getId(); ?>">
      <input type="submit" id="valider" class=pull-right name="modifier" value="Modifier"><br>
      <?php
      }
      ?>
    </form>
  </div>
</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="bootstrap/js/jquery-3.1.1.min.js"><\/script>')</script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="bootstrap/js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/offcanvas.js"></script>
  </body>
</html>
