<?php 
include 'authentification_process.php';

function connected($json_file = '../../data/users.json') {
    // On vérifie si l'utilisateur est connecté avec les variables de session :
    if (isset($_SESSION['username']) and isset($_SESSION['token'])) {
        $token = $_SESSION["token"];
        $json = getAllUsers($json_file);

        // Si l'adresse e-mail est une clé de la liste des utilisateurs ...
        if (array_key_exists($_SESSION['username'], $json)) {
            if (password_verify($token, $json[$_SESSION["username"]]["token"])) {
                return [True, $json[$_SESSION['username']]];
            } else { // Le token n'est pas correct
                return connected_cookie($json_file); // On verifie quand même dans les cookies
            }
        } else { // L'utilisateur n'est pas enregistré dans la base de données
            return connected_cookie($json_file); // On verifie au cas ou les cookies
        }
    } else { // L'utilisateur n'a pas de variables de SESSION permettant de l'identifier
        return connected_cookie($json_file); // On regarde dans les cookies
    }
}
    
    
function connected_cookie($json_file) {
    if (isset($_COOKIE["username"]) and isset($_COOKIE["token"])) {
        $token = $_COOKIE["token"];
        $json = getAllUsers($json_file);

        // Si l'adresse e-mail est une clé de la liste des utilisateurs ...
        if (array_key_exists($_COOKIE['username'], $json)) {
            if (password_verify($token, $json[$_COOKIE["username"]]["token"])) {
                // Enregistrement des données dans la session de l'utilisateur
                $_SESSION['username'] = $_COOKIE["username"];
                $_SESSION['token'] = $_COOKIE["token"];
                return [True, $json[$_COOKIE['username']]];
            } else { // Le token ne correspond pas à celui de la base de données
                return [False]; // REFUS DE CONNEXION
            }
        } else { // L'utilisateur n'est pas authentifier dans la base de données
            return [False]; // REFUS DE CONNEXION
        }
    } else { // Aucun cookie d'authentification
        return [False]; // REFUS DE CONNEXION
    }
}
?>