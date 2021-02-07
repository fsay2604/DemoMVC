<?php // La variable $title servira pour la balise <title> dans le fichier template.php ?>
<?php $title = 'Authentification'; ?>

<?php //Démarre la tamporisation du contenu ?>
<?php ob_start(); ?>

<?php
if(isset($_REQUEST['err']))
    echo $_REQUEST['err'];
?>
<h1>Se connecter</h1>

<form action="../mvc/index.php" method="post">
    <label for="courriel">Courriel:</label> <input type="email" autocomplete="username" name="courriel" id="courriel" placeholder="example@gmail.com">
    <br>
    <label for="passwd">Mot de passe:</label>   <input type="password"  autocomplete="current-password" name="passwd" id="passwd">
    <br>
    <label for="rememberMe">Se souvenir de moi</label> <input type="checkbox" name="rememberMe" id="rememberMe">
    <br>
    <input type="hidden" name="action" value="authentifier">
    <button type="submit"><span>Se connecter</span></button>
</form>
<div class="g-signin2" data-onsuccess="onSignIn"></div>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>







