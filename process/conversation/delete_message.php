<?php 
session_start();

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorHandler');

try {
    $MessageId = $_POST["id"];
    $Channel = $_POST["ch"];
    $ChannelFile = '../../data/conversation/' . $Channel . ".json";

    if (!file_exists($ChannelFile)) {
        throw new Exception('Conversation file does not exist');
    }

    $json = json_decode(file_get_contents($ChannelFile), true);

    foreach ($json['messages'] as &$message) {
        if ($message['id'] == $MessageId) {
            if ($message['author'] == $_SESSION['username']) {
                $message["status"] = "DELETED";
                break;
            }
            
        }
    }

    $newJson = json_encode($json, JSON_PRETTY_PRINT);

    // Écrire le contenu JSON modifié dans le fichier
    file_put_contents($ChannelFile, $newJson);

} catch (Exception $e) {
    http_response_code(500);
    exit;
}

?>