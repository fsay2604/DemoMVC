<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <link href="./inc/css/style.css" rel="stylesheet" />
    <meta name="google-signin-client_id" content="96138609572-8c7009tia6kp8hjv0k04av80el4ag6jf.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="inc/js/script.js" defer></script>

</head>

<body>
    <nav>
        <ul>
            <li><a href="index.php"><?php echo _('Accueil')?></a></li>
            <li><a href="produits"><?php echo _('Les produits')?></a></li>
            <?php
            if (isset($_SESSION['courriel']))
                echo '<a href="index.php?action=logout" onclick="signOut();">Sign out</a>';
            else 
            {
                echo '<li><a href="inscription">' . _('Inscription') . '</a></li>';
                echo '<li><a href="connexion">' . _('Connexion') . '</a></li>';
            }
            ?>
            <li><a href="./index.php?lang=fr_CA"><?php echo _('Francais') ?></a></li>
            <li><a href="./index.php?lang=en_US"><?php echo _('Anglais') ?></a></li>
            <li><a href="./index.php?lang=pt_BR"><?php echo _('Português') ?></a></li>
        </ul>
    </nav>
    <?= $content ?>
</body>

</html>