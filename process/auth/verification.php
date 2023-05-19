<?php 
include 'authentification_process.php';

function connected($json_file = '../../data/users.json') {
    // On vérifie si l'utilisateur est connecté avec les variables de session :
    if (isset($_SESSION['username']) and isset($_SESSION['token'])) {
        $json = getAllUsers($json_file);

        // Si l'adresse e-mail est une clé de la liste des utilisateurs ...
        if (array_key_exists($_SESSION['username'], $json)) {
            if ($_SESSION['token'] === $json[$_SESSION['username']]['token']) {
                return [True, $json[$_SESSION['username']]];
            } else {
                return connected_cookie($json_file);
            }
        } else {
            return connected_cookie($json_file);
        }
    } else {
        return connected_cookie($json_file);
    }
}
    
    
function connected_cookie($json_file) {
    if (isset($_COOKIE["username"]) and isset($_COOKIE["token"])) {
        $json = getAllUsers($json_file);

        // Si l'adresse e-mail est une clé de la liste des utilisateurs ...
        if (array_key_exists($_COOKIE['username'], $json)) {
            if ($_COOKIE['token'] === $json[$_COOKIE['username']]['token']) {
                // Enregistrement des données dans la session de l'utilisateur
                $_SESSION['username'] = $_COOKIE["username"];
                $_SESSION['token'] = $_COOKIE["token"];
                return [True, $json[$_COOKIE['username']]];
            } else {
                return [False];
            }
        } else {
            return [False];
        }
    } else {
        return [False];
    }
}














?>