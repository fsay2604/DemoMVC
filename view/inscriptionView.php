<?php // La variable $title servira pour la balise <title> dans le fichier template.php 
    $title = 'Inscription'; 
    ob_start(); //Démarre la tamporisation du contenu
 ?>

<h1>S'inscrire</h1>

<form action="../mvc/index.php" method="post">
    <label for="nom">Nom : </label><input type="text" name="nom" id="nom"><br>
    <label for="prenom">Prenom : </label><input type="text" name="prenom" id="prenom"><br>
    <label for="courriel">Courriel : </label><input type="email" name="courriel" id="courriel"><br>
    <label for="mdp">Mot de passe : </label><input type="password" name="mdp" id="mdp"><br>

    <input type="hidden" name="action" value="demandeInscription">
    <button type="submit">S'inscrire</button>
</form>

<?php 
    $content = ob_get_clean(); 
    require('template.php');
 ?>