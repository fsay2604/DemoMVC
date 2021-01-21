<?php 
// Gestion du titre
$prefixe = "Les produits ";
$title = '';

// Si une variable $categorie provenant d<une page qui appelle produitsView.php existe:
if(isset($categorie))
    $title = $prefixe . " de categorie " .  $categorie; // concatene pour ajuster le titre.
?>

<?php ob_start(); ?>
<h1><?= $title ?></h1>

<?php foreach($produits as $produit) { ?>
    <div>
        <h3>Produit: <?= htmlspecialchars($produit->get_produit()) ?> </h3>        
        <p>Description: <?= htmlspecialchars($produit->get_description()) ?> </p>        
        <hr>
    </div>
<?php } ?>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>