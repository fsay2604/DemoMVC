<?php
    //Débogage afficher ce qui est reçu en paramètres
    //phpinfo();
    echo "----------------------------<br/>";
    echo "Paramètres reçus:<br/><pre>";
    print_r($_REQUEST);
    echo "</pre>----------------------------<br/>";
?>

<?php
    // Verifie si la session est encore active
    // METTRE DANS L'INDEX ET UTILISER LE CONTROLLER
    if(!isset($_SESSION['courriel']))
    {
        // Sinon on regarde le cookie autologin
        if(isset($_COOKIE['Autologin']) && isset($_COOKIE['UserId']))
        {
            require_once('model/UtilisateurManager.php');
            $user = new UtilisateurManager;
            $user->verify_autoLogin();
        }
    }

    // Impression de la salutation si une session est active
    if(isset($_SESSION['courriel']))
        echo "<h2>Bienvenue " . $_SESSION['courriel'] . "</h2> <br>";


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link href="inc/css/style.css" rel="stylesheet" /> 
        <meta name="google-signin-client_id" content="96138609572-8c7009tia6kp8hjv0k04av80el4ag6jf.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        <script src="inc/js/script.js" defer></script>
    </head>
        
    <body>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="produits">Les produits</a></li>
                <?php
                    if(isset($_SESSION['courriel']))
                    {
                        echo '<a href="index.php?action=logout" onclick="signOut();">Sign out</a>';
                    }
                    else
                    {
                        echo '<li><a href="inscription">Inscription</a></li>';
                        echo '<li><a href="connexion">Connexion</a></li>';  
                    }
                        
                ?>
            </ul>
        </nav>
        <?= $content ?>
    </body>
</html>