<?php

require('model/ProduitManager.php');

function listProduits()
{
    $produitManager = new ProduitManager();
    $produits = $produitManager->getProduits();

    require('view/produitsView.php');
}

function produit()
{
    $produitManager = new ProduitManager();
    $produit = $produitManager->getProduit($_GET['id']);    

    require('view/produitView.php');
}

function listProduitCategorie()
{
    $produitManager = new ProduitManager();
    $produits = $produitManager->getProduitCategorie($_GET['id']);   

    // Debugging du contenu de l'array produits
    /*echo "<pre>";
    print_r($produits);
    echo "</pre>";*/

    $categorie = $produits[0]->get_categorie();

    require('view/produitsView.php');
}