<?php 

//Lire le JSON reçu dans le BODY
$data = json_decode(file_get_contents('php://input'), true);

if ($data['event_type'] == "PAYMENT.CAPTURE.COMPLETED")
{
    $id_transaction_paypal = strval(basename($data['resource']['links'][2]['href'])); // collecte le id_transaction_paypal qui est le meme que celui deja dans la bd
    
    if($data['resource']['status'] == 'COMPLETED')
        $status = intval(1);

    require('controller/controllerCommande.php');
    updateCommandeToDatabaseWithWebhookPayment($id_transaction_paypal, $status);
}
elseif ($data['event_type'] == "CHECKOUT.ORDER.APPROVED")
{
    // Arrive apres le Payment capture.
    $id_transaction_paypal = strval($data['resource']['id']);
    $id_payer_paypal = strval($data['resource']['payer']['payer_id']);
    $email_paypal = strval($data['resource']['payer']['email_address']);
    $date_facturation_paypal = explode("T", $data['resource']['update_time'])[0];

    require('controller/controllerCommande.php');
    updateCommandeToDatabaseWithWebhookCheckoutOrder($id_transaction_paypal, $id_payer_paypal, $email_paypal, $date_facturation_paypal);
}
?>