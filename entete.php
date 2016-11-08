<?php
if (isset($_POST['valider_connexion'])) {
    if (isset($_POST['identifiant']))
        $identifiant_a_tester = htmlspecialchars($_POST['identifiant']);
    if (isset($_POST['mot_de_passe']))
        $mot_de_passe_a_tester = htmlspecialchars($_POST['mot_de_passe']);
    if (!empty($identifiant_a_tester) && !empty($mot_de_passe_a_tester)) {
        $retour = $bdd->prepare('SELECT username, password FROM user WHERE username=:identifiant');
        $retour->bindValue('identifiant', $identifiant_a_tester, PDO::PARAM_STR);
        $retour->execute();
        $nb_resultats = $retour->rowCount();
        if ($nb_resultats == 1) {
            //$retour->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::user);
            $user = $retour->fetch();
            if (password_verify($mot_de_passe_a_tester, $user['password'])) {
                $_SESSION['identifiant'] = $user['username'];
                header('Location: index.php');
            } else {
                $erreur = "Le mot de passe n'est pas correct.";
            }
        } else {
            $erreur = "L'identifiant indiqué n'est pas correct.";
        }
    } else {
        $erreur = "Vous devez absolument remplir les champs \"identifiant\" et \"mot de passe\".";
    }
}
?>

<header>
    <nav class="navbar navbar-fixed-top navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Développer la navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Playlist app</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <?php
                if (isset($_SESSION['identifiant'])) {
                    ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['identifiant']; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="mes-playlists.php">Mes playlists</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="deconnexion.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    </ul>

                    <?php
                } else {
                    ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li<?php if ($_SERVER['PHP_SELF'] == '/02tracks/bd/inscription.php') echo ' class="active"'; ?>><a href="inscription.php">Inscription</a></li>
                        <li>
                            <form class="navbar-form navbar-right" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="form-group">
                                    <input type="text" name="identifiant" class="form-control" placeholder="Identifiant">
                                    <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe">
                                </div>
                                <button type="submit" name="valider_connexion" class="btn btn-default">Valider</button>
                            </form>

                        </li>
                    </ul>

                    <?php
                }
                ?>
            </div><!-- /.nav-collapse -->
        </div><!-- /.container -->
    </nav><!-- /.navbar -->
</header>
