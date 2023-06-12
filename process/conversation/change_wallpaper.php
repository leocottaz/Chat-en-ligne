<?php 
session_start();

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorHandler');

try {
    $Channel = $_POST["ch"];
    $Color = $_POST["color"];
    date_default_timezone_set('UTC');
    $MessageDate = date('Y-m-d\TH:i');
    $ChannelFile = '../../data/conversation/' . $Channel . ".json";

    if (!file_exists($ChannelFile)) {
        throw new Exception('Conversation file does not exist');
    }

    $json = json_decode(file_get_contents($ChannelFile), true);
    $user = $_SESSION["username"];
    $MessageId = count($json["messages"]) + 1;

    // Ajout du nouveau message au tableau "messages"
    $json['header']["color"] = $Color;

    $MessageContent = sprintf("j'ai modifié la couleur du tchat en : <div class='color_badge' style='background-color: %s;'></div>", $Color);

    $Message = array(
        "author" => $user,
        "id" => $MessageId,
        "read" => false,
        "status" => "OK",
        "system" => true,
        "date" => $MessageDate,
        "content" => $MessageContent
    );

    $json['messages'][] = $Message;

    // Conversion du tableau associatif en JSON
    $jsonUpdated = json_encode($json, JSON_PRETTY_PRINT);

    // Écriture du contenu JSON dans le fichier
    file_put_contents($ChannelFile, $jsonUpdated);

    echo $Color . "<!!delimiter!!>";
    echo "<div class='message user-message'>$MessageContent</div>";

} catch (Exception $e) {
    http_response_code(500);
    exit;
}
?>