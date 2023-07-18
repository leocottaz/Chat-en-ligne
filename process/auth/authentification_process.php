<?php 

function checkBruteForce($username) {
    $file = '../../data/connexion.json';
    $json = json_decode(file_get_contents($file), true);

    $AdresseIP = $_SERVER['REMOTE_ADDR'];
    $Time = time();

    if (!isset($json[$AdresseIP][$username])) {
        $json[$AdresseIP][$username] = [
            'count' => 1,
            'last_attempt' => $Time
        ];
    } else {
        $last_attempt = $json[$AdresseIP][$username]['last_attempt'];
        if ($Time - $last_attempt < 900) { // 900 secondes = 15 minutes
            $json[$AdresseIP][$username]['count']++;
        } else {
            // Réinitialisation des tentatives après 15 minutes
            $json[$AdresseIP][$username]['count'] = 1;
            $json[$AdresseIP][$username]['last_attempt'] = $Time;
        }
    }

    // Sauvegarde des tentatives de connexion
    file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));

    // Vérifier si les tentatives dépassent le seuil
    if ($json[$AdresseIP][$username]['count'] > 5) {
        // You can also log or notify about the blocked attempt here
        return true; // Bloqué
    }

    return false; // Pas bloqué
}

/**
 * Renvoie tous les utilisateurs enregistrés
 */
function getAllUsers($json_file = '../../data/users.json') {
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
    return password_hash($text, PASSWORD_BCRYPT);;
}

/**
 * Génère un token de 256 bits
 */
function generateToken() {
    $token = random_bytes(64);
    $token = base64_encode($token);
    $token = strtoupper($token);

    // CREATION D'UNE ID UNIQUE EN FONCTION DE L'HORODATAGE
    $user_id = uniqid('', False); // On génère un username unique pour l'utilisateur avec la fonction uniqid() en lui passant comme préfixe "user_".
    $user_id = strtoupper($user_id);

    // Création d'une id privée
    $private_id = random_bytes(15);
    $private_id = base64_encode($private_id); // Convertir les bytes en une chaîne hexadécimale    
    $private_id = strtoupper($private_id);

    return [$token, $user_id, $private_id];
}

function verify_password_validity($password) {
    if(strlen($password) < 6 || strlen($password) > 32) {
        die('Le mot de passe doit contenir entre 6 et 32 charactères.');
    }
}

function verify_username_validity($username) {
    if(strlen($username) < 6 || strlen($username) > 32) {
        die("Le nom d'utilisateur doit contenir entre 6 et 32 charactères.");
    }
}

function getUser($username, $json_file = '../../data/users.json') {
    // Récupération de la liste de tous les utilisateurs
    $json = getAllUsers($json_file);

    // Si l'adresse e-mail est une clé de la liste des utilisateurs ...
    return array_key_exists($username, $json)
        ? $json[$username] // alors: retourne la valeur qui correspond
        : False;           // sinon: retourne faux
}

function updateJson($user_data, $json_file = '../../data/users.json') {
    try {
        // Conversion de la liste des utilisateurs en JSON indenté
        $content = json_encode($user_data, JSON_PRETTY_PRINT);
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
        'token'    => encrypt($user_ids[0]),
        'friends' => []
    ];
    // Sauvegarde de la liste des utilisateurs
    updateJson($json);

    // Enregistrement des données dans la session de l'utilisateur
    $_SESSION['username'] = $username;
    $_SESSION['token'] = $user_ids[0];

    // On l'enregistre 30 jour ssi necessaire
    if ($remember == "1") {
        setcookie("username", $username, time()+60*60*24*30, '/'); // On crée un cookie qui contient l'username unique de l'utilisateur pour le garder authentifier
        setcookie("token", $user_ids[0], time()+60*60*24*30, '/');
    } else {
        // On supprime les cookies
        setcookie("username", '', time()-3600, '/');
        setcookie("token", '', time()-3600, '/');
    }
}

/**
 * Enregistre un nouvel utilisateur
 * @param string $username Adresse e-mail de l'utilisateur
 * @param string $password Mot de passe non hashé
 */

function register($username, $password, $remember) {
    
    verify_username_validity($username);
    verify_password_validity($password);

    // Récupération de l'utilisateur demandé
    $user = getUser($username);
    // Si l'utilisateur existe déjà, on arrête tout
    if($user) {
        die( "L'utilisateur {$username} est déjà enregistré." );
    }
    
    // Si les deux fonctions sont validées :
    addUser($username, $password, $remember);
}

/**
 * Tente de connecter un utilisateur. Affecte les sessions.
 * @param string $username Nom d'utilisateur de l'utilisateur
 * @param string $password Mot de passe non hashé
 */
function login($username, $password, $remember) {

    if (checkBruteForce($username)) {
        die("Tentatives de connexion bloquées. Réessayez plus tard.");
    }

    verify_username_validity($username); // Entre 6 et 32 charactères
    verify_password_validity($password); // Entre 6 et 32 charactères
    
    // Récupération de l'utilisateur
    $json = getAllUsers();

    // Si l'utilisateur n'a pas pu être récupéré.
    if(!array_key_exists($username, $json) ) {
        die( "L'utilisateur {$username} n'est pas enregistré." );
    }

    if (!password_verify($password, $json[$username]['password'])) {
        die( "Mot de passe incorrect" );
    }

    // Génération d'un nouveau token de sécurité.
    $token = generateToken()[0];

    // Enregistrement du nouveau token et sauvegarde des utilisateurs
    $json[$username]['token'] = encrypt($token);
    updateJson($json);

    // Enregistrement des données dans la session de l'utilisateur
    $_SESSION['username'] = $username;
    $_SESSION['token'] = $token;

    if ($remember == "1") {
        setcookie("username", $username, time()+60*60*24*30, '/', '', true, true); // Le dernier argument true active le marquage Secure et HttpOnly.
        setcookie("token", $token, time()+60*60*24*30, '/', '', true, true);
    } else {
        // On supprime les cookies
        setcookie("username", '', time()-3600, '/');
        setcookie("token", '', time()-3600, '/');
    }

    session_regenerate_id(true);
}
?>