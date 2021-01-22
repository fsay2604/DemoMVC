<?php
require('model/UtilisateurManager.php');

function getFormConnexion()
{
    require('view/loginView.php');
}

function authentifier($courriel, $mdp)
{
    $userController = new UtilisateurManager;
    $userController->verifAuthentification($courriel, $mdp);
    Header('Location: http://localhost/mvc/');
}

function authentifierGoogle($id_token)
{
    $userController = new UtilisateurManager;
    $userController->authentificationGoogle($id_token);
}
?>
