<?php

require('model/ProduitManager.php');

function listProduits()
{
    $produitManager = new ProduitManager();
    $produits = $produitManager->getProduits();

    require('controller/controllerCategorie.php');
    $categories = getCategories();

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

    $categorie = $produits[0]->get_categorie();

    require('view/produitsView.php');
}

function addProduit($id_categorie, $produit, $description)
{
    $produitManager = new ProduitManager();
    $produitManager->add($id_categorie, $produit, $description);   
}

function deleteProduit($id_produit)
{
    $produitManager = new ProduitManager();
    $produitManager->delete($id_produit);   
}