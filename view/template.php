<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <link href="inc/css/style.css" rel="stylesheet" /> 
    </head>
        
    <body>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="produits">Les produits</a></li>
            </ul>
        </nav>
        <?= $content ?>
    </body>
</html>