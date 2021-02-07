<?php

require('model/CategorieManager.php');

function listCategories()
{
    $categorieManager = new CategorieManager();
    $categories = $categorieManager->getCategories();

    require('view/categoriesView.php');
}

function getCategories()
{
    $categorieManager = new CategorieManager();
    $categories = $categorieManager->getCategories();

    return $categories;
}