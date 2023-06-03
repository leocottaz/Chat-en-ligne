<?php 
session_start();
function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('errorHandler');

try {
    $Channel = $_POST["ch"];
    $Color = $_POST["color"];
    $ChannelFile = '../../data/conversation/' . $Channel . ".json";
    $decode = file_get_contents($ChannelFile);
    $json = json_decode($decode, true);

    // Ajout du nouveau message au tableau "messages"
    $json['header']["color"] = $Color;

    // Conversion du tableau associatif en JSON
    $jsonUpdated = json_encode($json, JSON_PRETTY_PRINT);   

    // Écriture du contenu JSON dans le fichier
    file_put_contents($ChannelFile, $jsonUpdated); 
    
    echo $Color;
    
} catch (Exception $e) {
    http_response_code(500);
    exit;
}



?>