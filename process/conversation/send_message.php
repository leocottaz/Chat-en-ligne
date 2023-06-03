<?php 
session_start();
function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('errorHandler');

try {
    $MessageAuthor = $_SESSION["username"];
    date_default_timezone_set('UTC');
    $MessageDate = date('Y-m-d\TH:i');
    $MessageContent = $_POST["content"];
    $Channel = $_POST["ch"];
    $ChannelFile = '../../data/conversation/' . $Channel . ".json";
    $decode = file_get_contents($ChannelFile);
    $json = json_decode($decode, true);
    $MessageId = count($json["messages"]) + 1;

    if (empty($MessageContent)) {
        exit;
    } else {
       // Nouveau message à ajouter
    $Message = array(
            "author" => $MessageAuthor,
            "id" => $MessageId,
            "read" => False,
            "status" => "OK",
            "date" => $MessageDate,
            "content" => $MessageContent
    );

    // Ajout du nouveau message au tableau "messages"
    $json['messages'][] = $Message;

    // Conversion du tableau associatif en JSON
    $jsonUpdated = json_encode($json, JSON_PRETTY_PRINT);   

    // Écriture du contenu JSON dans le fichier
    file_put_contents($ChannelFile, $jsonUpdated); 
    echo "<div class='message user-message' messageId='$MessageId'><button class='delete_button' onclick='DeleteMessage($MessageId)'>Delete</button>" . htmlspecialchars($MessageContent) . "</div>";
    }
    
} catch (Exception $e) {
    http_response_code(500);
    exit;
}



?>