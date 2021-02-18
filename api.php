<?php
//Quelle est le type de la requête (GET, POST, PUT, DELETE, PATCH, etc.)
$request_method = $_SERVER["REQUEST_METHOD"];

//Le contenu sera formater en JSON (Peut être mis en commentaire pour fin de debogage)
header('Content-Type: application/json');

//Est-ce qu'une action a été reçue ?
if (isset($_REQUEST['objet'])) 
{
    //Qu'est-ce que l'API va gérer
    switch ($_REQUEST['objet'])
    {
        case 'Produit':

            //Quel type de commande ?
            switch ($request_method) 
            {
                case 'GET':
                    // Si un id produit est specifier, renvoyer seulement les informations de ce produit.
                    if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0)
                    {
                        require_once('controller/controllerProduit.php');
                        $produit = produit(true);
                        echo json_encode($produit, JSON_PRETTY_PRINT);
                    }
                    else  // Sinon, on envoit une liste de tout les produits.
                    {
                        require_once('controller/controllerProduit.php');
                        $productList = listProduits(true);
                        echo json_encode($productList, JSON_PRETTY_PRINT);
                    }
                    break;

                case 'POST':
                    //Ajout de 1 produit besoin de paramètres

                    //$data contiendra le JSON reçu et sera sous forme d'un array PHP 
                    $data = json_decode(file_get_contents('php://input'), true);

                    //valide si les paramètre necessaire a l'ajout sont pr/sent
                    if (!isset($data['produit'])) {
                        header('400 Bad Request');
                        echo '{"erreur":"produit manquant"}';
                        die();
                    }
                    if (!isset($data['id_categorie']) && $data['id_categorie'] < 0) {
                        header('400 Bad Request');
                        echo '{"erreur":"id_categorie manquant"}';
                        die();
                    }
                    if (!isset($data['description'])) {
                        header('400 Bad Request');
                        echo '{"erreur":"description manquante"}';
                        die();
                    }

                    //Appel au controlleur de produts pour ensuite appeler une fonction qui fait l'ajout d'un produit
                    require_once('controller/controllerProduit.php');
                    addProduit($data['id_categorie'], $data['produit'], $data['description']);
                    echo '{"succes":"insertion du produit dans la BD avec succes."}';
                    break;
                
                case 'DELETE':
                    //valide si les paramètre necessaire a l'ajout sont présent
                    if (!isset($_REQUEST['id_produit'])) {
                        header('400 Bad Request');
                        echo '{"erreur":"id produit manquant"}';
                        die();
                    }

                    //Appel au controlleur de produts pour ensuite appeler une fonction qui fait la suppression d'un produit
                    require_once('controller/controllerProduit.php');
                    deleteProduit($_REQUEST['id_produit']);
                    echo '{"succes":"Suppression du produit avec succes."}';
                    break;

                default:
                    echo 'ERROR';
                    //Erreur
                    break;
            } // nested switch ends here
            break;
    } // switch ends here
} // if ends here
?>