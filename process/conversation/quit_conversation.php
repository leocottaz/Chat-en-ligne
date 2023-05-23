<?php 
session_start();
function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('errorHandler');

try {
    $Channel = $_POST["ch"];
if (empty($Channel)) {
    exit;
}
$ChannelFile = '../../data/conversation/' . $Channel . ".json";
$decode = file_get_contents($ChannelFile);
$json = json_decode($decode, true);

$json["header"]["user"][$_SESSION["username"]][0] = "DISCONNECTED";

// Réencoder le tableau en JSON
$newJson = json_encode($json, JSON_PRETTY_PRINT);

// Écrire le contenu JSON modifié dans le fichier
file_put_contents($ChannelFile, $newJson);
} catch (Exception $e) {
    echo $e;
}

?>