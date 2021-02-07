<?php 
// Gestion du titre
$prefixe = "Les produits ";

// Si une variable $categorie provenant d<une page qui appelle produitsView.php existe:
if(isset($categorie))
    $title = $prefixe . " de categorie " .  $categorie; // concatene pour ajuster le titre.
else
    $title = $prefixe;
?>

<?php ob_start(); ?>
<h1 style><?= $title ?></h1><input id="btn_addProduct" type="image" src="inc/img/add-icon.png" width=24 height=24 name="ajouter">

<!-- Gestion de l'ajout d'un produit -->
<div class="form_addProduct hidden">
    <form id="form_add" action="../mvc/index.php" method="post"> <!-- onSubmit="return false"-->
        <fieldset >
        <legend>Gestion des produits:</legend>
            <label for="produit">Produit: </label><input type="text" name="produit" id="produit"><br>
            <label for="categorie">Catégorie: </label> 
                <select name="categorie" id="categorie">
                    <?php foreach($categories as $categorie) { ?>
                        <option value="<?= $categorie->get_id_categorie() ?>"><?= $categorie->get_categorie() ?></option>
                    <?php } ?>
                </select><br>
            <label for="description">Description: </label><input type="text" name="description" id="description"><br>
            <button id="btn_SendNewProduct" type="submit">Ajouter</button>            
        </fieldset>
    </form>
</div>

<?php foreach($produits as $produit) { ?>
    <div class="produit">
        <h3>Produit: <?= htmlspecialchars($produit->get_produit()) ?> </h3> <input type="image" src="inc/img/delete-icon.png" width=24 height=24 name="delete" class="removeProduct" value="<?=$produit->get_id_produit()?>">        
        <p>Description: <?= htmlspecialchars($produit->get_description()) ?> </p>        
        <hr>
    </div>
<?php } ?>



<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>