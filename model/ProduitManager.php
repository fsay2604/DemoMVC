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
        $req = $db->query('SELECT * FROM tbl_produit ORDER BY id_produit');

        $produits = array();

        while($data = $req->fetch()){
            array_push($produits, new Produit($data));
        }

        $req->closeCursor();
        return $produits;
    }

    public function getProduit($produitId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p.*, categorie FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE id_produit = ?');
        $req->execute(array($produitId));
        $produit = new Produit($req->fetch());

        return $produit;
    }

    public function getProduitCategorie($idCategorie)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p.*, categorie FROM tbl_produit AS p INNER JOIN tbl_categorie AS c ON p.id_categorie = c.id_categorie WHERE c.id_categorie = ?'); 
        $req->execute(array($idCategorie));

        $produits = array();

        while($data = $req->fetch())
        {
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
    public function add($id_categorie, $produit, $description)
    {
        $db = $this->dbConnect();
        echo "$id_categorie , $produit, $description";
        $req = $db->prepare('INSERT INTO tbl_produit(id_categorie, produit, description) VALUES (?,?,?)'); 
        $req->execute(array($id_categorie, $produit, $description));
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
}