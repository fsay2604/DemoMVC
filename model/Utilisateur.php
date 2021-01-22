<?php

class Utilisateur
{
    private $_id_utilisateur;
    private $_nom;
    private $_prenom;
    private $_courriel;
    private $_mdp;
    private $_est_actif;            // 0 = inactif, 1 = actif
    private $_role_utilisateur;     // 0 = normal,  1 = admin
    private $_type_utilisateur;     // 0 = normal,  1 = Google
    private $_token;                // Contiendra une chaine de caractere pour la fonction "Se souvenir de moi".



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
     * Get the value of _nom
     */ 
    public function get_nom()
    {
        return $this->_nom;
    }

    /**
     * Set the value of _nom
     *
     * @return  self
     */ 
    public function set_nom($_nom)
    {
        $this->_nom = $_nom;

        return $this;
    }

    /**
     * Get the value of _prenom
     */ 
    public function get_prenom()
    {
        return $this->_prenom;
    }

    /**
     * Set the value of _prenom
     *
     * @return  self
     */ 
    public function set_prenom($_prenom)
    {
        $this->_prenom = $_prenom;

        return $this;
    }

    /**
     * Get the value of _courriel
     */ 
    public function get_courriel()
    {
        return $this->_courriel;
    }

    /**
     * Set the value of _courriel
     *
     * @return  self
     */ 
    public function set_courriel($_courriel)
    {
        $this->_courriel = $_courriel;

        return $this;
    }

    /**
     * Get the value of _mdp
     */ 
    public function get_mdp()
    {
        return $this->_mdp;
    }

    /**
     * Set the value of _mdp
     *
     * @return  self
     */ 
    public function set_mdp($_mdp)
    {
        $this->_mdp = $_mdp;

        return $this;
    }

    /**
     * Get the value of _est_actif
     */ 
    public function get_est_actif()
    {
        return $this->_est_actif;
    }

    /**
     * Set the value of _est_actif
     *
     * @return  self
     */ 
    public function set_est_actif($_est_actif)
    {
        $this->_est_actif = $_est_actif;

        return $this;
    }

    /**
     * Get the value of _role_utilisateur
     */ 
    public function get_role_utilisateur()
    {
        return $this->_role_utilisateur;
    }

    /**
     * Set the value of _role_utilisateur
     *
     * @return  self
     */ 
    public function set_role_utilisateur($_role_utilisateur)
    {
        $this->_role_utilisateur = $_role_utilisateur;

        return $this;
    }

    /**
     * Get the value of _type_utilisateur
     */ 
    public function get_type_utilisateur()
    {
        return $this->_type_utilisateur;
    }

    /**
     * Set the value of _type_utilisateur
     *
     * @return  self
     */ 
    public function set_type_utilisateur($_type_utilisateur)
    {
        $this->_type_utilisateur = $_type_utilisateur;

        return $this;
    }

    /**
     * Get the value of _token
     */ 
    public function get_token()
    {
        return $this->_token;
    }

    /**
     * Set the value of _token
     *
     * @return  self
     */ 
    public function set_token($_token)
    {
        $this->_token = $_token;

        return $this;
    }
}

?>