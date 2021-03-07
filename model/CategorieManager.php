<?php

require_once("model/Manager.php");
require_once("model/Categorie.php");

class CategorieManager extends Manager
{
    public function getCategories()
    {
        $db = $this->dbConnect();
        // Creation de la requete en fonction de la langue
        switch ($_SESSION['lang']) {
            case "fr_CA":
                $req = $db->prepare('SELECT id_categorie, categorie_fr, description_fr FROM tbl_categorie ORDER BY id_categorie');
                break;
            case "en_US":
                $req = $db->prepare('SELECT id_categorie, categorie_en, description_en FROM tbl_categorie ORDER BY id_categorie');
                break;
            case "pt_BR":
                $req = $db->prepare('SELECT id_categorie, categorie_pt, description_pt FROM tbl_categorie ORDER BY id_categorie');
                break;
            default:
                $req = $db->prepare('SELECT id_categorie, categorie_en, description_en FROM tbl_categorie ORDER BY id_categorie');
        }
        $req->execute(array());

        $categories = array();
        while($data = $req->fetch()){
            array_push($categories, new Categorie($data));
        }

        $req->closeCursor();
        return $categories;
    }
}