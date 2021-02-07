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
            if (isset($_SESSION['courriel'])) {
                echo '<a href="index.php?action=logout" onclick="signOut();">Sign out</a>';
            } else {
                echo '<li><a href="inscription">Inscription</a></li>';
                echo '<li><a href="connexion">Connexion</a></li>';
            }

            ?>
        </ul>
    </nav>
    <?= $content ?>
</body>

</html>