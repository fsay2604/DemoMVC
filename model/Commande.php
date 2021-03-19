<?php
class Commande
{
    private $_id_commande;
    private $_id_utilisateur;
    private $_statut;
    private $_date_achat;
    private $_id_transaction_paypal; 
    private $_id_payer_paypal;
    private $_email_paypal;
    private $_date_facturation_paypal;

    public function __construct($params = array())
    {
        foreach ($params as $k => $v) {
            $methodName = "set_" . $k;

            if (method_exists($this, $methodName)) {
                $this->$methodName($v);
            }
        }
    }

    /**
     * Get the value of _id_commande
     */ 
    public function get_id_commande()
    {
        return $this->_id_commande;
    }

    /**
     * Set the value of _id_commande
     *
     * @return  self
     */ 
    public function set_id_commande($_id_commande)
    {
        $this->_id_commande = $_id_commande;

        return $this;
    }

    /**
     * Get the value of _id_utilisateur
     */ 
    public function get_id_utilisateur()
    {
        return $this->_id_utilisateur;
    }

    /**
     * Set the value of _id_utilisateur
     *
     * @return  self
     */ 
    public function set_id_utilisateur($_id_utilisateur)
    {
        $this->_id_utilisateur = $_id_utilisateur;

        return $this;
    }

    /**
     * Get the value of _statut
     */ 
    public function get_statut()
    {
        return $this->_statut;
    }

    /**
     * Set the value of _statut
     *
     * @return  self
     */ 
    public function set_statut($_statut)
    {
        $this->_statut = $_statut;

        return $this;
    }

    /**
     * Get the value of _date_achat
     */ 
    public function get_date_achat()
    {
        return $this->_date_achat;
    }

    /**
     * Set the value of _date_achat
     *
     * @return  self
     */ 
    public function set_date_achat($_date_achat)
    {
        $this->_date_achat = $_date_achat;

        return $this;
    }

    /**
     * Get the value of _id_transaction_paypal
     */ 
    public function get_id_transaction_paypal()
    {
        return $this->_id_transaction_paypal;
    }

    /**
     * Set the value of _id_transaction_paypal
     *
     * @return  self
     */ 
    public function set_id_transaction_paypal($_id_transaction_paypal)
    {
        $this->_id_transaction_paypal = $_id_transaction_paypal;

        return $this;
    }

    /**
     * Get the value of _id_payer_paypal
     */ 
    public function get_id_payer_paypal()
    {
        return $this->_id_payer_paypal;
    }

    /**
     * Set the value of _id_payer_paypal
     *
     * @return  self
     */ 
    public function set_id_payer_paypal($_id_payer_paypal)
    {
        $this->_id_payer_paypal = $_id_payer_paypal;

        return $this;
    }

    /**
     * Get the value of _email_paypal
     */ 
    public function get_email_paypal()
    {
        return $this->_email_paypal;
    }

    /**
     * Set the value of _email_paypal
     *
     * @return  self
     */ 
    public function set_email_paypal($_email_paypal)
    {
        $this->_email_paypal = $_email_paypal;

        return $this;
    }

    /**
     * Get the value of _date_facturation_paypal
     */ 
    public function get_date_facturation_paypal()
    {
        return $this->_date_facturation_paypal;
    }

    /**
     * Set the value of _date_facturation_paypal
     *
     * @return  self
     */ 
    public function set_date_facturation_paypal($_date_facturation_paypal)
    {
        $this->_date_facturation_paypal = $_date_facturation_paypal;

        return $this;
    }
}
?>