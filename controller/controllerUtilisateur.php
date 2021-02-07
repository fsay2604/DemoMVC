<?php
require('model/UtilisateurManager.php');

function getFormConnexion()
{
    require('view/loginView.php');
}

function getFormInscription()
{
    require('view/inscriptionView.php');
}

function authentifier($courriel, $mdp)
{
    $userController = new UtilisateurManager;
    $userController->verifAuthentification($courriel, $mdp);
}

function authentifierGoogle($id_token)
{
    $userController = new UtilisateurManager;
    $userController->authentificationGoogle($id_token);
}

function logout()
{
    $user = new UtilisateurManager;
    $user->disable_autologin();
}

function add_user_db($nom, $prenom, $courriel, $mdp)
{
    $userController = new UtilisateurManager;
    $userController->add_db($nom, $prenom, $courriel, $mdp);
}

function checkTokenInscription($token)
{
    $userController = new UtilisateurManager;
    $userController->checkTokenInscription($token);
}
?>
