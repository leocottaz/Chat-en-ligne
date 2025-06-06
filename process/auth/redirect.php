<?php
session_start();

// Récupération des variables du formulaire
$username = isset($_POST['username']) ? $_POST['username'] : ''; // On récupère la valeur de l'input username du formulaire si elle existe sinon on initialise la variable à une chaîne vide.
$password = isset($_POST['password']) ? $_POST['password'] : ''; // On récupère la valeur de l'input password du formulaire si elle existe sinon on initialise la variable à une chaîne vide.

// On enleve les espaces non intentionnel en fin de chaine :
$username = rtrim($username);
$password = rtrim($password);
include "./authentification_process.php"; // On inclut le fichier authentification_process.php qui contient les fonctions nécessaires à l'authentification.
		
	if ($_GET["a"] === "login") { // Si la requête viens de login.php et non de register.php
		
		login($username, $password, $_POST['remember']);
		header('Location: ../../user/main.php');
		exit;

	} else { // Si la requête viens de register.php et non de login.php
			
		register($username,$password, $_POST['remember']); // Fonction permettant d'écrire l'ulisateur dans la base de donnée
		header('Location: ../../help/start.php'); // Redirection vers les tchats
    	exit;
	}
?>