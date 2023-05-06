<?php 
$json_file = '../../data/users.json';

/**
 * Renvoie tous les utilisateurs enregistrés
 */
function getAllUsers() {
    global $json_file;
    try {
        $decode = file_get_contents($json_file);
        $json = json_decode($decode, true);
        return $json;
    }
    catch( Exception $e ) { // Gestion d'erreur lors de l'ouverture du json
      die( $e->getMessage() );
    }
  }

/**
 * Encryptage (sans décryptage possible du mot de passe)
 */
function encrypt($text) {
    return sha1($text);
}

/**
 * Génère un token de 256 bits
 */
function generateToken() {
    $token = random_bytes(32);
    $token = bin2hex($token);

    // CREATION D'UNE ID UNIQUE EN FONCTION DE L'HORODATAGE
    $user_id = uniqid('', False); // On génère un username unique pour l'utilisateur avec la fonction uniqid() en lui passant comme préfixe "user_".
    
    // Création d'une id privée
    $bytes = random_bytes(15);
    $private_id = bin2hex($bytes); // Convertir les bytes en une chaîne hexadécimale    

    return [$token, $user_id, $private_id];
}

function getUser($username) {
    // Récupération de la liste de tous les utilisateurs
    $json = getAllUsers();

    // Si l'adresse e-mail est une clé de la liste des utilisateurs ...
    return array_key_exists($username, $json)
        ? $json[$username] // alors: retourne la valeur qui correspond
        : False;           // sinon: retourne faux
}

function updateJson($user_data) {
    try {
        global $json_file;
        // Conversion de la liste des utilisateurs en JSON indenté
        $content = json_encode( $user_data, JSON_PRETTY_PRINT );
        // Remplacement du contenu du fichier.
        file_put_contents($json_file, $content);
    }
    catch( Exception $e ) {
        die( $e->getMessage() );
    }
}

function addUser($username, $password, $remember) {
    // Récupération de la liste de tous les utilisateurs
    $json = getAllUsers();
    $user_ids = generateToken();

    // Ajout du nouvel utilisateur
    $json[$username] = [
        'password' => encrypt($password),
        'right' => False,
        'private_id' => $user_ids[2],
        'public_id' => $user_ids[1],
        'token'    => $user_ids[0],
        'friends' => []
    ];
    // Sauvegarde de la liste des utilisateurs
    updateJson($json);

    // On l'enregistre 30 jour ssi necessaire
    if ($remember == "1") {
        setcookie("private_key", $json[$username]["private_id"], time()+60*60*24*30, '/'); // On crée un cookie qui contient l'username unique de l'utilisateur pour le garder authentifier
        setcookie("token", $user_ids[0], time()+60*60*24*30, '/');
    }
}

/**
 * Enregistre un nouvel utilisateur
 * @param string $username Adresse e-mail de l'utilisateur
 * @param string $password Mot de passe non hashé
 */

function register($username, $password, $remember) {
    // Récupération de l'utilisateur demandé
    $user = getUser($username);
    // Si l'utilisateur existe déjà, on arrête tout
    if( $user ) {
        die( "L'utilisateur {$username} est déjà enregistré." );
    }

    // Enregistrement du nouvel utilisateur
    addUser($username, $password, $remember);
}

/**
 * Tente de connecter un utilisateur. Affecte les sessions.
 * @param string $username Adresse e-mail de l'utilisateur
 * @param string $password Mot de passe non hashé
 */
function login($username, $password, $remember) {
    // Récupération de l'utilisateur
    $json = getAllUsers();

    if(isset($_COOKIE['private_key'])){
        setcookie("private_key", "null", time() - 3600, '/'); // On supprime les cookies
        setcookie("token", "null", time() - 3600, '/');
      } else {
        // Faites quelque chose si le cookie n'est pas défini
      }

    // Si l'utilisateur n'a pas pu être récupéré.
    if( ! array_key_exists($username, $json) ) {
        die( "L'utilisateur {$username} n'est pas enregistré." );
    }

    // Si le mot de passe (hashé) ne correspond pas, on arrête tout.
    if( $json[$username]['password'] !== encrypt($password) ) {
        die( "L'utilisateur {$username} n'est pas enregistré." );
    }

    // Génération d'un nouveau token de sécurité.
    $token = generateToken()[0];

    // Enregistrement du nouveau token et sauvegarde des utilisateurs
    $json[$username]['token'] = $token;
    updateJson($json);

    // Enregistrement des données dans la session de l'utilisateur
    $_SESSION['user_username'] = $username;
    $_SESSION['user_token'] = $token;

    if ($remember == "1") {
        setcookie("private_key", $json[$username]["private_id"], time()+60*60*24*30, '/'); // On crée un cookie qui contient l'username unique de l'utilisateur pour le garder authentifier
        setcookie("token", $token, time()+60*60*24*30, '/');
    }
}
?>