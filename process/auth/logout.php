<?php 
setcookie("private_key", False, time() - 3600,'/'); // Suppresion des cookies permettant de rester connecté
setcookie("token", False, time() - 3600,'/'); // Suppresion des cookies permettant de rester connecté
header('Location: ../../index.php'); // Redirection vers main.php
exit;
?>