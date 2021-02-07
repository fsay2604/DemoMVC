<?php
session_start();

// Verifie si la session est encore active
if (!isset($_SESSION['courriel'])) {
    // Sinon on regarde le cookie autologin
    if (isset($_COOKIE['Autologin']) && isset($_COOKIE['UserId'])) {
        // require_once('model/UtilisateurManager.php');
        require_once('controller/controllerUtilisateur.php');
        checkAutologin();
    }
}

// Impression de la salutation si une session est active
if (isset($_SESSION['courriel']))
    echo "<h2>Bienvenue " . $_SESSION['courriel'] . "</h2> <br>";

// Impression de l'erreur si paramater 'err'    
if (isset($_REQUEST['err'])) {
    echo "<h2>" . $_REQUEST['err'] . "</h2><br>";
}                  // Impression des erreurs

if (isset($_REQUEST['action']))                                        //  Est-ce qu'un paramètre action est présent
{
    if ($_REQUEST['action'] == 'logout')                                 // Si on appuie sur signout
    {
        require_once('controller/controllerUtilisateur.php');
        logout();

        // Destruction des cookies.
        if (isset($_COOKIE['UserID'])) {
            unset($_COOKIE['UserID']);
            setcookie('UserID', '', time() - 3600, '/');                // empty value and old timestamp
        }
        if (isset($_COOKIE['Autologin'])) {
            unset($_COOKIE['Autologin']);
            setcookie('Autologin', '', time() - 3600, '/');
        }

        session_destroy();
        Header("Location: http://localhost/mvc/index.php");
    } else if ($_REQUEST['action'] == 'produits')                      //    Est-ce que l'action demandée est la liste des produits
    {
        require('controller/controllerProduit.php');
        listProduits();
    } elseif ($_REQUEST['action'] == 'produit')                      // Sinon est-ce que l'action demandée est la description d'un produit
    {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0)           //  Est-ce qu'il y a un id en paramètre
        {
            require('controller/controllerProduit.php');
            produit();
        } else
            echo 'Erreur : aucun identifiant de produit envoyé';    //  Si on n'a pas reçu de paramètre id, mais que la page produit a été appelé
    } elseif ($_REQUEST['action'] == 'categories')                  // Sinon est-ce que l<action demandee est l<affichage des categories
    {
        require('controller/controllerCategorie.php');
        listCategories();
    } elseif ($_REQUEST['action'] == 'produitscategories')          // Si on filtre selon la categorie
    {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0) {
            require('controller/controllerProduit.php');
            listProduitCategorie();
        }
    } elseif ($_REQUEST['action'] == 'connexion')                         // Si on appuie sur le bouton connexion du formulaire.
    {
        require('controller/controllerUtilisateur.php');
        getFormConnexion();
    } elseif ($_REQUEST['action'] == "authentifier")                        // Si on veut s<authentifier avec un compte google
    {
        if (isset($_REQUEST['courriel']) && isset($_REQUEST['passwd'])) {
            require('controller/controllerUtilisateur.php');
            authentifier($_REQUEST['courriel'], $_REQUEST['passwd']);
        } else if (isset($_REQUEST['idtoken'])) {
            require('controller/controllerUtilisateur.php');
            authentifierGoogle($_REQUEST['idtoken']);
        } else
            Header("Location: http://localhost/mvc/index.php?action=connexion&err=erreur_connexion");

        if (isset($_SESSION['courriel']))                                // On redirige a l'acceuil si l'utilisateur a reussi a se connecter.
            Header("Location: http://localhost/mvc/");
    } else if ($_REQUEST['action'] == 'inscription')                       // si on appuie sur le lien inscription dans le nav.
    {
        require('controller/controllerUtilisateur.php');
        getFormInscription();
    } else if ($_REQUEST['action'] == 'demandeInscription')               // Quand on remplit le formulaire d<inscription et qu'on appuie sur le bouton
    {
        require('controller/controllerUtilisateur.php');
        if (isset($_REQUEST['nom']) && isset($_REQUEST['prenom']) && isset($_REQUEST['courriel']) && isset($_REQUEST['mdp']))
            add_user_db($_REQUEST['nom'], $_REQUEST['prenom'], $_REQUEST['courriel'], $_REQUEST['mdp']);
    } else if ($_REQUEST['action'] == 'validationCourriel')               // Si on appuie sur le lien dans le courriel d'inscription
    {
        require('controller/controllerUtilisateur.php');
        if (isset($_REQUEST['token']))
            checkTokenInscription($_REQUEST['token']);
        else
            echo ' Token not set.';
    } else if ($_REQUEST['action'] == 'AjoutProduit')                     // Si on appuie sur l'image d'ajout d'un produit
    {
        echo "!!!!!!";
        require('controller/controllerProduit.php');
        addProduit($_REQUEST['categorie'], $_REQUEST['produit'], $_REQUEST['description']);
    } else if ($_REQUEST['action'] == 'DeleteProduit')                    // Si on appuie sur l'image de suppression d'un produit
    {
        require('controller/controllerProduit.php');
        deleteProduit($_REQUEST['id_produit']);
    }
} else                                                                   // Si pas de paramètre charge l'accueil
{
    require('controller/controllerAccueil.php');
    listProduits();
}
