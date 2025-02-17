<?php

// Ce fichier sert à communiquer avec la BD et construire les objets pour les retourner au controleur.
// Ces fonctions sont généralement appelé par le routeur (index.php) ou d'autres contrôleurs.

require_once("model/Manager.php");
require_once("model/Produit.php");

class ProduitManager extends Manager
{
    public function getProduits()
    {
        $db = $this->dbConnect();
        // Creation de la requete en fonction de la langue
        switch($_SESSION['lang'])
        {
            case "fr_CA":
                $req = $db->prepare('SELECT id_produit, id_categorie, produit_fr, description_fr, prix FROM tbl_produit ORDER BY id_produit');
                break;
            case "en_CA":
                $req = $db->prepare('SELECT id_produit, id_categorie, produit_en, description_en, prix FROM tbl_produit ORDER BY id_produit');
                break;
            case "pt_BR":
                $req = $db->prepare('SELECT id_produit, id_categorie, produit_pt, description_pt, prix FROM tbl_produit ORDER BY id_produit');
                break;
            default:
                $req = $db->prepare('SELECT id_produit, id_categorie, produit_en, description_en, prix FROM tbl_produit ORDER BY id_produit');
        }
        $req->execute(array());

        $produits = array();
        while ($data = $req->fetch()) {
            array_push($produits, new Produit($data));
        }

        $req->closeCursor();
        return $produits;
    }// function ends here

    public function getProduit($produitId)
    {
        $db = $this->dbConnect();
        // Creation de la requete en fonction de la langue
        switch ($_SESSION['lang']) {
            case "fr_CA":
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_fr, p.description_fr, c.categorie_fr, p.prix FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE id_produit = ?');
                break;
            case "en_CA":
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_en, p.description_en, c.categorie_en, p.prix FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE id_produit = ?');
                break;
            case "pt_BR":
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_pt, p.description_pt, c.categorie_pt, p.prix FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE id_produit = ?');
                break;
            default:
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_en, p.description_en, c.categorie_en, p.prix FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE id_produit = ?');
        }
        $req->execute(array($produitId));
        $produit = new Produit($req->fetch());
        return $produit;
    }// functions ends here

    public function getProduitCategorie($idCategorie)
    {
        $db = $this->dbConnect();
        // Creation de la requete en fonction de la langue
        switch ($_SESSION['lang']) {
            case "fr_CA":
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_fr, p.description_fr, c.categorie_fr FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE c.id_categorie = ?');
                break;
            case "en_CA":
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_en, p.description_en, c.categorie_en FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE c.id_categorie = ?');                break;
            case "pt_BR":
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_pt, p.description_pt c.categorie_pt FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE c.id_categorie = ?');
                break;
            default:
                $req = $db->prepare('SELECT p.id_produit, p.id_categorie, p.produit_en, p.description_en, c.categorie_en FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE c.id_categorie = ?');
        }
        $req->execute(array($idCategorie));

        $produits = array();
        while ($data = $req->fetch()) {
            array_push($produits, new Produit($data));
        }

        $req->closeCursor();
        return $produits;
    }

    /**
     * Ajoute un produit dans la base de donnee
     * $id_categorie est l'id de la categorie du produit a ajouter.
     * $produit est le nom du produit
     * $description est la description du produit.
     */

     /**
      * TO DO: Ajouter un prix, donc ajouter un formulaire au champs du formulaire aussi.
      */

    public function add($id_categorie, $produit, $description, $prix) 
    {
        $db = $this->dbConnect();
        // Creation de la requete en fonction de la langue
        switch ($_SESSION['lang'])
        {
            case "fr_CA":
                $req = $db->prepare('INSERT INTO tbl_produit(id_categorie, produit_fr, description_fr, prix) VALUES (?,?,?,?)');
                break;
            case "en_CA":
                $req = $db->prepare('INSERT INTO tbl_produit(id_categorie, produit_en, description_en, prix) VALUES (?,?,?,?)');
                break;
            case "pt_BR":
                $req = $db->prepare('INSERT INTO tbl_produit(id_categorie, produit_pt, description_pt, prix) VALUES (?,?,?,?)');                
                break;
            default:
                $req = $db->prepare('INSERT INTO tbl_produit(id_categorie, produit_en, description_en, prix) VALUES (?,?,?,?)');        
        }
        $req->execute(array($id_categorie, $produit, $description, $prix));
        $req->closeCursor();
    }

    /**
     * Supprime un enregistrement dans la base de donnee.
     * $id_produit est le id du produit a supprimer.
     */
    public function delete($id_produit)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM tbl_produit WHERE id_produit = ?');
        $req->execute(array($id_produit));
        $req->closeCursor();
    }

} // class ends here
