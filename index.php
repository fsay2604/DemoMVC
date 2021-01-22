<?php
session_start(); 

//Est-ce qu'un paramètre action est présent
if (isset($_REQUEST['action'])) {

    //Est-ce que l'action demandée est la liste des produits
    if ($_REQUEST['action'] == 'produits') {
        //Ajoute le controleur de Produit
        require('controller/controllerProduit.php');
        //Appel la fonction listProduits contenu dans le controleur de Produit
        listProduits();
    }
    // Sinon est-ce que l'action demandée est la description d'un produit
    elseif ($_REQUEST['action'] == 'produit') {
        
        // Est-ce qu'il y a un id en paramètre
        if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0) {
            //Ajoute le controleur de Produit
            require('controller/controllerProduit.php');
            //Appel la fonction produit contenu dans le controleur de Produit
            produit();
        }
        else {
            //Si on n'a pas reçu de paramètre id, mais que la page produit a été appelé
            echo 'Erreur : aucun identifiant de produit envoyé';
        }
    }
    // Sinon est-ce que l<action demandee est l<affichage des categories
    elseif ($_REQUEST['action'] == 'categories') { 
        //Ajoute le controleur de categories
        require('controller/controllerCategorie.php');
        listCategories();
    }
    // Si on filtre selon la categorie
    elseif ($_REQUEST['action'] == 'produitscategories')
    {
        if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0)
        {
            //Ajoute le controleur de Produit
            require('controller/controllerProduit.php');
            listProduitCategorie();
        }
    }
    elseif ($_REQUEST['action'] == 'connexion')
    {
        require('controller/controllerUtilisateur.php');
        getFormConnexion();
    }
    elseif($_REQUEST['action'] == "authentifier")
    {
        if(isset($_REQUEST['idtoken']))
        {
            require('controller/controllerUtilisateur.php');
            authentifierGoogle($_REQUEST['idtoken']);
        }
        else if(isset($_REQUEST['courriel']) && isset($_REQUEST['passwd']))
        {
            require('controller/controllerUtilisateur.php');
            authentifier($_REQUEST['courriel'], $_REQUEST['passwd']);
        }
        else
        {
            echo "error";
        }
    }

}
// Si pas de paramètre charge l'accueil
else {
    //Ajoute le controleur de Produit
    require('controller/controllerAccueil.php');
    //Appel la fonction listProduits contenu dans le controleur de Produit
    listProduits();
}