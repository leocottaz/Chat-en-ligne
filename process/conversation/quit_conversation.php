<?php 
session_start();
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
?>