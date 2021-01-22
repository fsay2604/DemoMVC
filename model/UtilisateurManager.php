<?php

require_once("model/Manager.php");
require_once("model/Utilisateur.php");

class UtilisateurManager extends Manager
{
    // Verification de la tentative de connexion via les champs du formulaire.
    function verifAuthentification($courriel, $mdp)
    {
        // Connexion a la BD.
        $db = $this->dbConnect();

        // Recuperation des informations dans la BD.
        $req = $db->prepare('SELECT * FROM tbl_utilisateur WHERE courriel LIKE ?');
        $req->execute(array($courriel));                                                   
        $user = $req->fetch();

        // Gestion de la session
        if(password_verify($mdp, $user['mdp']) && $user['type_utilisateur'] == 0)
        {
            $_SESSION['courriel'] = $courriel;
            $_SESSION['role'] = $user['role_utilisateur'];
        }
        else
        {
            Header('Location: http://localhost/mvc/index.php?action=connexion&err=authError');
        }
    }

// Verification de la tentative de connexion avec google Auth.
function authentificationGoogle($id_token)
{
    require_once('inc/google-api/vendor/autoload.php');

    // Get $id_token via HTTPS POST.
    $CLIENT_ID = '96138609572-8c7009tia6kp8hjv0k04av80el4ag6jf.apps.googleusercontent.com';
    $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend

    $payload = $client->verifyIdToken($id_token);

    if ($payload) {
        $userid = $payload['sub'];
 
        // Connexion a la BD.
        $db = $this->dbConnect();

        // Recuperation des informations dans la BD pour verifier si l'utilisateur existe
        $req = $db->prepare('SELECT * FROM tbl_utilisateur WHERE courriel LIKE ?');
        $req->execute(array($payload['email']));   
        $data = $req->fetch();

        // Si l'utilisateur existe
        if($data)
        {
            // Ajout dans la session des variables courriels et role.
            $_SESSION['courriel'] = $data['courriel'];
            $_SESSION['role'] = $data['role_utilisateur'];
        }
        // Si l'utilisateur n'existe pas
        else
        {
            // Creation de l'utilisateur
            $req = $db->prepare('INSERT INTO tbl_utilisateur(nom, prenom, courriel, mdp, est_actif, role_utilisateur, type_utilisateur, token) VALUES (?, ?, ?, "", 1, 0, 1, "")');
            $req->execute(array($payload['family_name'], $payload['given_name'], $payload['email']));

            // Recuperation des informations dans la BD pour verifier si l'utilisateur existe
            $req = $db->prepare('SELECT courriel, role_utilisateur FROM tbl_utilisateur WHERE courriel LIKE ?');
            $req->execute(array($payload['email']));   
            $data = $req->fetch();

            // Ajout dans la session des variables courriels et role.
            $_SESSION['courriel'] = $data['courriel'];
            $_SESSION['role'] = $data['role_utilisateur'];
        }
    } 
    else
    {
        // Invalid ID token
    }
}
}
?>