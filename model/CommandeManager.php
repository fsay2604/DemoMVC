<?php

require_once("model/Manager.php");
require_once("model/Commande.php");

class CommandeManager extends Manager
{
    /**
     * Ajoute un produit dans la table tbl_commande_produit
     */
    public function addCommandeProduit($id_transact_paypal, $id_produit, $quantite, $prix_unitaire)
    {
        $db = $this->dbConnect();
        $req_commande_id = $db->prepare('SELECT id_commande FROM tbl_commande WHERE id_transaction_paypal = ?');
        $req_commande_id->execute(array($id_transact_paypal));
        $id_commande = $req_commande_id->fetch();

        print_r($id_commande);

        $req = $db->prepare('INSERT INTO tbl_commande_produit(id_commande, id_produit, quantite, prix_unitaire) VALUES (?,?,?,?)');
        $req->execute(array($id_commande[0], $id_produit, $quantite, $prix_unitaire));
        $req->closeCursor();
    }

    /**
     * Ajoute un enregistrement dans la table tbl_commande
     */
    public function addCommande($email_utilisateur, $statut, $date_achat, $id_transaction_paypal)
    {
        $db = $this->dbConnect();
        $req_user_id = $db->prepare('SELECT id_utilisateur FROM tbl_utilisateur WHERE courriel = ?');
        $req_user_id->execute(array($email_utilisateur));
        $id_utilisateur = $req_user_id->fetch();

        $req = $db->prepare('INSERT INTO tbl_commande(id_utilisateur, statut, date_achat, id_transaction_paypal) VALUES (?,?,?,?)');
        $req->execute(array($id_utilisateur[0], $statut, $date_achat, $id_transaction_paypal));
        $req->closeCursor();
    }
    /**
     * Met a jour la base de donnee avec les informations du webHook de paypal (CheckoutOrDer)
     */
    public function updateWithChecloutOrderWebhook($id_transaction_paypal, $id_payer_paypal, $email_paypal, $date_facturation_paypal)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE tbl_commande SET id_payer_paypal = ?, email_paypal = ?, date_facturation_paypal = ? WHERE id_transaction_paypal = ?');
        $req->execute(array($id_payer_paypal, $email_paypal, $date_facturation_paypal, $id_transaction_paypal));
        $req->closeCursor();
    }

    /**
     * Fonction qui update la table commande en fonction du webhook de paypal (PaymentCapture event)
     */
    public function updateWithPaymentCaptureWebhook($id_transaction_paypal, $status)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE tbl_commande SET statut = ? WHERE id_transaction_paypal = ?');
        $req->execute(array($status, $id_transaction_paypal));
        $req->closeCursor();
    }
}
?>