<?php

require('model/ProduitManager.php');

function listProduits($api_call = false)
{
    $produitManager = new ProduitManager();
    $produits = $produitManager->getProduits();

    require('controller/controllerCategorie.php');
    $categories = getCategories();

    if ($api_call == false)
        require('view/produitsView.php');
    else
    {
        $SerializeProducts = array();
        foreach ($produits as $p){
            array_push($SerializeProducts,$p->jsonSerialize());
        }
        return json_encode($SerializeProducts);
    }
}

function produit($api_call = false)
{
    $produitManager = new ProduitManager();
    $produit = $produitManager->getProduit($_REQUEST['id']);

    if($api_call == false)
        require('view/produitView.php');
    else
        return json_encode($produit->jsonSerialize());
}

function listProduitCategorie()
{
    $produitManager = new ProduitManager();
    $produits = $produitManager->getProduitCategorie($_REQUEST['id']);

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