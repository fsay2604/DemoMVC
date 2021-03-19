<?php
session_start();
echo '<pre>';
print_r($_REQUEST);
echo '</pre>';

// Gestion de la langues
if (isset($_REQUEST['lang'])) {
    switch ($_REQUEST['lang']) {
        case "fr_CA":
            $_SESSION['lang'] = "fr_CA";
            break;
        case "en_CA":
            $_SESSION['lang'] = "en_CA";
            break;
        case "pt_BR":
            $_SESSION['lang'] = "pt_BR";
            break;
        default:
            $_SESSION['lang'] = "en_CA";
    } //switch ends here
}
if (!isset($_SESSION['lang']))   // met en francais si la lang n'existe pas.
    $_SESSION['lang'] = "en_US";

//Chemin vers la racine du site Web
define('PROJECT_DIR', realpath('./'));

// Chemin vers le dossier qui contient les traductions (ici /locale)
define('LOCALE_DIR', PROJECT_DIR . '/locale');

//Langue par défaut
define('DEFAULT_LOCALE', 'en_CA');

// Require vers la librairie
require_once('./lib/gettext/gettext.inc');

//Déterminer la langue à utiliser. (ici on devrait déterminer quelle langue on souhaite utiliser)
$locale = $_SESSION['lang'];

//Assigner la langue aux paramètres
T_setlocale(LC_MESSAGES, $locale);

//Quel est le nom du fichier de traduction (.mo)
$domain = 'trad';
bindtextdomain($domain, LOCALE_DIR);

//Détermination de l'encodage
$encoding = 'UTF-8';
if (function_exists('bind_textdomain_codeset'))
    bind_textdomain_codeset($domain, $encoding);

//Application du dossier contenant la traduction
textdomain($domain);

// Fin des configurations.

//Pour gérer les pluriels !
// Variable qui déterminera si c'est singulier ou pluriel.
//$qt = 3;

//ngettext permet de gérer le singulier et pluriel,
// 1er argument => version singulier,
// 2e argument => version pluriel
// 3e argument => le nombre qui permet d'indiquer quelle version prendre

//sprintf permet de formater une chaine (justification, remplacer des paramètres). 
// Pour indiquer l'emplacement d'un nombre entier il faut mettre %d dans la chaine.
// les arguments 2 et + sont les valeurs pour remplacer les "tags" tel que %d

//echo sprintf(ngettext("%d commentaire", "%d commentaires", $qt), $qt);


// Verifie si la session est encore active
if (!isset($_SESSION['courriel'])) {
    // Sinon on regarde le cookie autologin
    if (isset($_COOKIE['Autologin']) && isset($_COOKIE['UserId'])) {
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
        require('controller/controllerProduit.php');
        addProduit($_REQUEST['categorie'], $_REQUEST['produit'], $_REQUEST['description'], $_REQUEST['prix']);
    } else if ($_REQUEST['action'] == 'DeleteProduit')                    // Si on appuie sur l'image de suppression d'un produit
    {
        require('controller/controllerProduit.php');
        deleteProduit($_REQUEST['id_produit']);
    }
    // Quand ont veut faire une commande d'achat
    else if ($_REQUEST['action'] == 'achatProduit')                    // Si on appuie sur l'image de suppression d'un produit
    {
        require('controller/controllerProduit.php');
        listProduits(2);
    }
    // Quand ont veut faire une commande d'achat
    else if ($_REQUEST['action'] == 'AddOrderToDB')                    // Si on appuie sur l'image de suppression d'un produit
    {
        require('controller/controllerCommande.php');
        // Commande
        if(isset($_SESSION['courriel']))
        {
            $status = 0;
            $date_achat = explode("T", $_REQUEST['date_achat'])[0];    // on ne prend que YYYY-MM-DD
            $id_transaction_paypal = strval($_REQUEST['id_transaction_paypal']); // strval pour mettre la valeur en string.

            // Insert dans un premier temps les informations de la commande (sauf ceux du webhook)
            addCommandeToDatabase($_SESSION['courriel'], $status, $date_achat, $id_transaction_paypal);
        
            // Ajout dans la table_commande_produit chacun des produits
            $ItemsStringToExplode = $_REQUEST['items'];
            $RetrievedItems = explode("/", $ItemsStringToExplode); // renvoit chacun des items
            $items = []; // Contiendra l'ensemble des items

            for($i = 0; $i < count($RetrievedItems); $i++)
                $items[$i] = explode(";", $RetrievedItems[$i]); // renvoit chacun des elements d<un items.

            for($i =0; $i < count($items)-1; $i++)
            {
                $id_produit = intval(explode("=", $items[$i][0])[1]);
                $prix_unitaire = floatval(explode("=", $items[$i][1])[1]);
                $quantite = intval(explode("=", $items[$i][2])[1]);
                addCommandeProduitToDatabase($id_transaction_paypal, $id_produit, $quantite, $prix_unitaire);
            }
        }
    }
    
} else                                                                   // Si pas de paramètre charge l'accueil
{
    require('controller/controllerAccueil.php');
    listProduits();
}
