<?php
function logout() {
unset($_SESSION["username"]);
unset($_SESSION["token"]);
setcookie("username", False, time() - 3600,'/'); // Suppresion des cookies permettant de rester connecté
setcookie("token", False, time() - 3600,'/'); // Suppresion des cookies permettant de rester connecté
header('Location: ../../index.php'); // Redirection vers shop.php
exit;
}
?>