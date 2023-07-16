<?php 
session_start();

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorHandler');

try {
    if (!isset($_POST["ch"])) {
        http_response_code(500);
        exit;
    }

    $Channel = $_POST["ch"];
    if (empty($Channel)) {
        http_response_code(500);
        exit;
    }

    $ChannelFile = '../../data/conversation/' . $Channel . "/messages.json";
    if (!file_exists($ChannelFile)) {
        http_response_code(500);
        exit;
    }

    $decode = file_get_contents($ChannelFile);
    $json = json_decode($decode, true);

    $json["header"]["user"][$_SESSION["username"]][0] = "DISCONNECTED";

    // Réencoder le tableau en JSON
    $newJson = json_encode($json, JSON_PRETTY_PRINT);

    // Écrire le contenu JSON modifié dans le fichier
    file_put_contents($ChannelFile, $newJson);
} catch (Exception $e) {
    http_response_code(500);
    exit;
}
?>