<?php

// Cette classe sert de point commun à tous les modèles et offre les fonctions génériques communes à tous.
// Dans notre cas ce sera l'accès à la BD.

class Manager {
    
    protected function dbConnect()
    {
        $db = new PDO('mysql:host=localhost;dbname=demomvc;charset=utf8', 'root', '');
        return $db;
    }
} 