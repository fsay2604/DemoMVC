<?php // La variable $title servira pour la balise <title> dans le fichier template.php 
$title = 'Commande';
ob_start(); //Démarre la tamporisation du contenu
?>
<div class="container">
    <!-- Titre des colonnes de champs -->
    <label class="achat_cell_lg" for="col_produit_titre"><?= _('Produit') ?></label>
    <label class="achat_cell_sm" for="col_quantite" id="label_qt"><?= _('Qt') ?></label>
    <label class="achat_cell_md" for="col_unites_titre"><?= _('$/unité') ?></label>
    <label class="achat_cell_md" for="col_total_titre"><?= _('Total') ?></label>
    <br>
    <!-- fait la liste des produits -->
    <?php
    foreach ($produits as $produit) {
    ?>
        <label class="achat_cell_lg product_name" for="$produit->get_produit()"><?= $produit->get_produit() ?></label>
        <input class="achat_cell_sm qty" type="number" name="quantity" min=0 value=0 data-id_produit="<?= $produit->get_id_produit() ?>">
        <div class="achat_unitePrix achat_cell_md prix"><?= $produit->get_prix() ?></div>
        <div class="achat_cell_md"><span class="achat_total">0.00</span></div>
        <br>
    <?php } ?>
    <!-- Grand Total -->
    <label class="achat_cell_sm" for="achat_grand_total">Total :</label>
    <div class="achat_total achat_cell_lg"><span id="achat_grand_total">0.00</span></div>


    <!-- Script qui charge le SDK-JavaScript de PayPal -->
    <script src="https://www.paypal.com/sdk/js?currency=CAD&client-id=AYRS8CjX2h4gT4wHqrqT9ut3e3irItSulUlbFV9Va_i180MSuAFGS02TFDWEeM8JzvdSE7ZX0eJ0xZzh">
        // Replace YOUR_CLIENT_ID with your sandbox client ID
    </script>

    <!-- div qui détermine où sera le bouton -->
    <div id="paypal-button-container"></div>

    <!-- Add the checkout buttons, set up the order and approve the order -->
    <script>
        function prepareItems(forDB = false) {
            let nb_qty = document.getElementsByClassName("qty");
            // Prepare les items pour le paypal API
            if (forDB == false) {
                let myItems = [];
                for (let i = 0; i < nb_qty.length; i++) {
                    if (nb_qty[i].value > 0) {
                        let ItemName = get_product_name(i);
                        let ItemValue = get_product_price(i);
                        let ItemQuantity = get_product_quantity(i);

                        let item = { //Array pour chaque item vendu dans la commande
                            name: ItemName, //Nom du produit
                            unit_amount: {
                                value: ItemValue,
                                currency_code: "CAD"
                            }, //Prix unitaire
                            quantity: ItemQuantity
                        }
                        myItems.push(item);
                    }
                }
                return myItems;
            }
            // Prepare les items pour l<ajout a la DB
            else {
                let myItems = "&items=";
                for (let i = 0; i < nb_qty.length; i++) {
                    if (nb_qty[i].value > 0) {
                        let itemID = "id_produit=" + get_product_id(i);
                        let ItemValue = "prix_unitaire=" + get_product_price(i);
                        let ItemQuantity = "quantity=" + get_product_quantity(i);

                        let item = itemID + ";" + ItemValue + ";" + ItemQuantity + "/";
                        myItems += item;
                    }
                }
                return myItems;
            }
        }

        // Fonction qui prend chacune des informations dans detaisl et qui renvoit une string.
        function getPaypalDetails(details) {
            let statut = "&status=" + details.status;
            let date_achat = "&date_achat=" + details.create_time;
            let id_transaction_paypal = "&id_transaction_paypal=" + details.id;

            let post_values = [];
            post_values.push(statut);
            post_values.push(date_achat);
            post_values.push(id_transaction_paypal);

            return post_values;
        }

        // Ajoute le produit dans la base de donnes lorsque la transaction est compl/t/.
        function DBOrder(details) {
            let post_values = "action=AddOrderToDB"
            let post_details = getPaypalDetails(details); // Renvoit les details de Details (api php)
            let post_items = prepareItems(true); // Contient tout les infos du produit

            // Construction de la string pour passer en post.
            for (let i = 0; i < post_details.length; i++)
                post_values += ("&" + post_details[i]);

            post_values += ("&" + post_items);

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200)
                    window.location.reload(); 

                }

                xhttp.open('POST', 'http://localhost/mvc/index.php');
                xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhttp.send(post_values); // param avec post
            }

            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                currency_code: 'CAD',
                                value: calculer_grand_total(), //valeur total
                                breakdown: {
                                    item_total: {
                                        value: calculer_grand_total(),
                                        currency_code: "CAD"
                                    }
                                } // valeur total
                            },
                            items: prepareItems(false)
                        }]
                    });
                },
                onApprove: function(data, actions) { // Lorsque la commande est approuvée on arrive ici
                    return actions.order.capture().then(function(details) {
                        alert('Transaction completed by ' + details.payer.name.given_name); //la variable details contient des infos sur la commande
                        console.log(details);
                        // Ajout dans la bd ici
                        DBOrder(details);
                    });
                }
            }).render('#paypal-button-container'); // Display payment options on your web page
    </script>
</div>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>