<?php 
session_start();
function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('errorHandler');

$MessageId = $_POST["id"];
$Channel = $_POST["ch"];
$ChannelFile = '../../data/conversation/' . $Channel . ".json";
$decode = file_get_contents($ChannelFile);
$json = json_decode($decode, true);

try {
    foreach ($json['messages'] as &$message) {
        if ($message['id'] == $MessageId) {
            $message["status"] = "DELETED";
            break;
        }
    }

    $newJson = json_encode($json, JSON_PRETTY_PRINT);

    // Écrire le contenu JSON modifié dans le fichier
    file_put_contents($ChannelFile, $newJson);

} catch (Exception $e) {
    echo "<div class='message error-message'> Une erreur est survenue lors de la suppression du message </div>" . $e;
}










?>