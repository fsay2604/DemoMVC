<?php
class Categorie 
{
    private $_id_categorie;
    private $_categorie; // N'est pas dans la table produit, mais les requêtes vont aussi chercher le nom de la catégorie en faisant une jointure.
    private $_description;

    public function __construct($params = array()){
  
        foreach($params as $k => $v){

            $methodName = "set_" . $k;
            if(method_exists($this, $methodName)) {
                $this->$methodName($v);
            }   
        }
    }

    /**
     * Get the value of _id_categorie
     */ 
    public function get_id_categorie()
    {
        return $this->_id_categorie;
    }

    /**
     * Set the value of _id_categorie
     *
     * @return  self
     */ 
    public function set_id_categorie($_id_categorie)
    {
        $this->_id_categorie = $_id_categorie;

        return $this;
    }

    /**
     * Get the value of _categorie
     */ 
    public function get_categorie()
    {
        return $this->_categorie;
    }

    /**
     * Set the value of _categorie
     *
     * @return  self
     */ 
    public function set_categorie($_categorie)
    {
        $this->_categorie = $_categorie;

        return $this;
    }
    /**
     * Get the value of _description
     */ 
    public function get_description()
    {
        return $this->_description;
    }

    /**
     * Set the value of _description
     *
     * @return  self
     */ 
    public function set_description($_description)
    {
        $this->_description = $_description;

        return $this;
    }

}
?>