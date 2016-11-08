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

$retour = $bdd->prepare('DELETE FROM playlist_track WHERE playlist_id=:playlist_id AND track_id=:track_id');
$retour->bindValue('playlist_id', $playlist_id, PDO::PARAM_INT);
$retour->bindValue('track_id', $track_id, PDO::PARAM_INT);
$retour->execute();
$retour->closeCursor();

header('Location: ma-playlist.php?playlist_id='.$playlist_id);  
?>
