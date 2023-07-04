<?php 
session_start();

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorHandler');

try {
    $user = $_SESSION["username"];
    $target = $_POST["user"];

    $json = json_decode(file_get_contents("../../data/users.json"), true);

    if (isset($json[$user]["friends"][$target])) {
        unset($json[$user]["friends"][$target]);
    }

    if(array_key_exists($target, $json)) {
        if (isset($json[$target]["friends"][$user])) {
            $json[$target]["friends"][$user]["status"] = "DELETED";
        }
    }
    

    $newJson = json_encode($json, JSON_PRETTY_PRINT);

    // Écrire le contenu JSON modifié dans le fichier
    file_put_contents("../../data/users.json", $newJson);

} catch (Exception $e) {
    http_response_code(500);
    exit;
}

?>