<?php $title = 'Categories'?>

<?php ob_start(); ?>
<h1>Les categories</h1>

<?php foreach($categories as $categorie) { ?>
    <div>
        <h3>Categorie: <?= htmlspecialchars($categorie->get_categorie()) ?> </h3>        
        <p>Description: <?= htmlspecialchars($categorie->get_description()) ?> </p>

        <a href="produitscategories/<?= $categorie->get_id_categorie() ?>">Voir les produits</a>
        <hr>
    </div>
<?php } ?>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>