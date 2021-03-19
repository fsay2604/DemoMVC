<?php

require('model/CommandeManager.php');

function addCommandeProduitToDatabase($id_transact_paypal, $id_produit, $quantite, $prix_unitaire)
{
    $commandeManager = new CommandeManager();
    $commandeManager->addCommandeProduit($id_transact_paypal, $id_produit, $quantite, $prix_unitaire);
}

function addCommandeToDatabase($email_utilisateur, $statut, $date_achat, $id_transaction_paypal)
{
    $commandeManager = new CommandeManager();
    $commandeManager->addCommande($email_utilisateur, $statut, $date_achat, $id_transaction_paypal);
}


function updateCommandeToDatabaseWithWebhookCheckoutOrder($id_transaction_paypal, $id_payer_paypal, $email_paypal, $date_facturation_paypal)
{
    $commandeManager = new CommandeManager();
    $commandeManager->updateWithChecloutOrderWebhook($id_transaction_paypal, $id_payer_paypal, $email_paypal, $date_facturation_paypal);
}

function updateCommandeToDatabaseWithWebhookPayment($id_transaction_paypal, $status)
{
    $commandeManager = new CommandeManager();
    $commandeManager->updateWithPaymentCaptureWebhook($id_transaction_paypal, $status);
}
?>
