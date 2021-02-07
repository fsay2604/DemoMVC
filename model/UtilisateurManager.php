<?php
require_once("model/Manager.php");
require_once("model/Utilisateur.php");
require_once("inc/Util.php");

class UtilisateurManager extends Manager
{
    // Verification de la tentative de connexion via les champs du formulaire.
    function verifAuthentification($courriel, $mdp)
    {
        // Connexion a la BD.
        $db = $this->dbConnect();

        // Recuperation des informations dans la BD.
        $req = $db->prepare('SELECT * FROM tbl_utilisateur WHERE courriel LIKE ?');
        $req->execute(array($courriel));                                                   
        $user = $req->fetch();
        $req->closeCursor(); // Fermeture du cursor.

        // Gestion de la session
        if(password_verify($mdp, $user['mdp']) && $user['type_utilisateur'] == 0 && $user['est_actif'] == 1) // A verifier
        {
            // Ajoute les infos dans la session.
            $_SESSION['courriel'] = $courriel;
            $_SESSION['role'] = $user['role_utilisateur'];

            // Si la case rememberMe du form. est cocher
            if(isset($_REQUEST['rememberMe']))
            {
                // Genere un token
                $util = new Util();
                $token = $util->getToken(12);
                $token_hash = password_hash($token, PASSWORD_DEFAULT);

                // Creation des cookie
                $cookie_name = "Autologin";
                $cookie_value = $token;
                $date_expiration = new Datetime('+30 days');
                setcookie($cookie_name, $cookie_value, $date_expiration->format(DateTime::COOKIE), "/");  

                $cookie_name = "UserId";
                $cookie_value = $user['id_utilisateur'];
                setcookie($cookie_name, $cookie_value, $date_expiration->format(DateTime::COOKIE), "/");  
               
                // Ajoute dans la base de donnees de l'enregistrement.
                $this->addAutoLogin($user['id_utilisateur'], $token_hash, $date_expiration);
            }
        }
        else
            Header('Location: http://localhost/mvc/index.php?action=connexion&err=authError');
    }

    // Verification de la tentative de connexion avec google Auth.
    function authentificationGoogle($id_token)
    {
        require_once('inc/google-api/vendor/autoload.php');

        // Get $id_token via HTTPS POST.
        $CLIENT_ID = '96138609572-8c7009tia6kp8hjv0k04av80el4ag6jf.apps.googleusercontent.com';
        $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend

        $payload = $client->verifyIdToken($id_token);

        if ($payload) 
        {
            $db = $this->dbConnect(); // Connexion a la BD.

            // Recuperation des informations dans la BD pour verifier si l'utilisateur existe
            $req = $db->prepare('SELECT * FROM tbl_utilisateur WHERE courriel LIKE ?');
            $req->execute(array($payload['email']));   
            $data = $req->fetch();
            $req->closeCursor(); // Fermeture du cursor.

            // Si l'utilisateur existe
            if($data && $data['est_actif'] == 1) // A Verfier
            {
                // Ajout dans la session des variables courriels et role.
                $_SESSION['courriel'] = $data['courriel'];
                $_SESSION['role'] = $data['role_utilisateur'];
            }
            // Si l'utilisateur n'existe pas
            else
            {
                // Creation de l'utilisateur
                $req = $db->prepare('INSERT INTO tbl_utilisateur(nom, prenom, courriel, mdp, est_actif, role_utilisateur, type_utilisateur, token) VALUES (?, ?, ?, "", 1, 0, 0, "")');
                $req->execute(array($payload['family_name'], $payload['given_name'], $payload['email']));

                // Ajout dans la session des variables courriels et role.
                $_SESSION['courriel'] = $data['courriel'];
                $_SESSION['role'] = $data['role_utilisateur'];
            }
        }
    }

    /**
     * Fonction qui ajoute une entree dans la table autologin de la DB.
     * @param $id_utulisateur contient le id de l<utilisateur.
     * @param $token_hash contient le token_hash
     * @param $date_expiration contient la date d'expiration de l'autoLogin (meme que celle du cookie)
     */
    function addAutoLogin($id_utilisateur, $token_hash, $date_expiration)
    {
        // Connexion a la BD.
        $db = $this->dbConnect();

        // Verification si une entree autoLogin existe deja
        $req = $db->prepare('SELECT * FROM tbl_autologin WHERE id_utilisateur = ?');
        $req->execute(array($id_utilisateur));
        $data = $req->fetch();
        $req->closeCursor(); // Fermeture du cursor.

        // Si l'utilisateur n'a pas d'autoLogin
        if($data == false)
        {
            $date_expiration = $date_expiration->format('Y-m-d');
            $est_valide = 1;
            // Requete
            $req = $db->prepare('INSERT INTO tbl_autologin(id_utilisateur, token_hash, est_valide, date_expiration) VALUES (?, ?, ?, ?)');
            $req->execute(array($id_utilisateur, $token_hash, $est_valide, $date_expiration));
            $req->closeCursor(); // Fermeture du cursor.
        }
        else if ( $data == true && $data['est_valide'] == 0)
        {
            $db = $this->dbConnect();       // Connexion a la BD.

            // update est_valide de la table autoLogin pour 1
            $req = $db->prepare('UPDATE tbl_autologin SET est_valide = 1 WHERE id_utilisateur = (SELECT id_utilisateur FROM tbl_utilisateur WHERE courriel = ?)');
            $req->execute(array($data['id_utilisateur'])); 
            $req->closeCursor(); // Fermeture du cursor.
        }
    }

    /**
     * Verify dans la base de donnee si le token_hash relier au token existe.
     * Return true s'il existe
     */
    function verify_autoLogin()
    {
        $today = new DateTime('now');   // Date du jour
        $today = $today->format('Y-m-d'); // change le format pour que ce sois la meme change que dans la db

        $db = $this->dbConnect();       // Connexion a la BD.

        // Collecte des informations sur l'auto login
        $req = $db->prepare('SELECT * FROM tbl_autologin WHERE id_utilisateur = ?');
        $req->execute(array($_COOKIE['UserId']));
        $autologin_data = $req->fetch();
        $req->closeCursor(); // Fermeture du cursor.
        
        if($autologin_data['est_valide']) // Si l'autologin est encore valide.
        {   
            if($today <= $autologin_data['date_expiration']) // Verifie si la date dans la BD n<est pas plus grande que la date d'aujourd'hui
            {
                if(password_verify($_COOKIE['Autologin'], $autologin_data['token_hash']))   // Si le token dans le cookie correspond au token_hash dans la bd A DEBUGGER
                {
                    // Recuperation des informations dans la BD.
                    $req = $db->prepare('SELECT * FROM tbl_utilisateur WHERE id_utilisateur LIKE ?');
                    $req->execute(array($autologin_data['id_utilisateur']));                                                   
                    $user = $req->fetch();
                    $req->closeCursor(); // Fermeture du cursor.

                    // Ajoute les infos dans la session.
                    $_SESSION['courriel'] = $user['courriel'];
                    $_SESSION['role'] = $user['role_utilisateur'];
                }
            }
            else // si la date d'aujourdhui est superieur a la date d'expiration du cookie
            {
                // update est_valide de la table autoLogin pour 0
                $this->disable_autologin();
                
                // Redirection
                Header("Location: http://localhost/mvc/");
            }
        }
    }

    // Desactive le cookie dans la base de donner dans la table autoLogin
    function disable_autologin()
    {
        $db = $this->dbConnect();       // Connexion a la BD.

        // update est_valide de la table autoLogin pour 0
        $req = $db->prepare('UPDATE tbl_autologin SET est_valide = 0 WHERE id_utilisateur = (SELECT id_utilisateur FROM tbl_utilisateur WHERE courriel = ?)');
        $req->execute(array($_SESSION['courriel'])); 
        $req->closeCursor(); // Fermeture du cursor.
    }

    // Ajoute a la base de donnee un utilisateur
    function add_db($nom, $prenom, $courriel, $mdp)
    {
        $db = $this->dbConnect(); // Se connecter a la DB

        // Verifier si un utilisateur avec le courriel existe deja
        $req = $db->prepare('SELECT * FROM tbl_utilisateur WHERE courriel = ?');
        $req->execute(array($courriel)); 
        $data = $req->fetch();

        // Si l'utilisateur existe deja, on ne veut pas lui envoyer de courriel ni le creeer
        if($data)
        {
            Header("Location: http://localhost/mvc/index.php?err=Utilisateur_deja_existant");
        }
        else
        {
            // Genere un token
            $util = new Util();
            $token = $util->getToken(12);

            // Creation d'un nouvel utilisateur dans la BDD
            $req = $db->prepare('INSERT INTO tbl_utilisateur(nom, prenom, courriel, mdp, est_actif, role_utilisateur, type_utilisateur, token) VALUES (?, ?, ?, ?, 0, 0, 0, ?)');
            $req->execute(array($nom, $prenom, $courriel, password_hash($mdp, PASSWORD_DEFAULT), $token)); 

            // Init des variables pour courriel
            $emailFrom = "hebert.francois.charles@gmail.com";
            $to = "thatinyguyinshorts@gmail.com";
            $subject = "Test d'inscription";
            $url="http://localhost/mvc/index.php?action=validationCourriel&token=$token";
            $message = '
                        <html>
                        <head>
                        <title>Validation</title>
                        </head>
                        <body>
                        <p>Follow this link: </p> <a href="' . $url . '">Valider</a>
                        </body>
                        </html>
                        ';
            $header = "From: FC <$emailFrom>\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type:text/html; charset=UTF-8\r\n"; 
           
            if(mail($to, $subject, $message, $header)) // Envoie du courriel pour l'inscription
                echo 'Mail envoyer';
            else
                echo 'Echec de lenvoi mail';     
        }
    }

    function checkTokenInscription($token)
    {
        $db = $this->dbConnect(); // Se connecter a la DB

        // Verifier si un utilisateur avec le courriel existe deja
        $req = $db->prepare('SELECT * FROM tbl_utilisateur WHERE token = ?');
        $req->execute(array($token)); 
        $data = $req->fetch();

        if($data && $data['est_actif'] == 0)   // si un utilisateur a ce token la
        {
            // Mettre le compte actif.
            $req = $db->prepare('UPDATE tbl_utilisateur SET est_actif=1 WHERE token = ?');
            $req->execute(array($token)); 

            Header("Location: http://localhost/mvc/connexion");
        }
        else
        {
            Header("Location: http://localhost/mvc/index.php?err=Account_Already_Activated");
        }
    }
}
