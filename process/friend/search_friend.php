<?php 
session_start();
function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('errorHandler');

function random_code() {
    $code = random_bytes(10);
    $code = bin2hex($code);
    $code = strtoupper($code);

    return $code;
}

 try {
    $target = $_POST["target"];

    if(empty($target)) {
        echo "...... Qui voulez vous demander ?";
        exit;
    }
    
    $author = $_SESSION["username"];
    $FilePath = "../../data/users.json";
    $decode = file_get_contents($FilePath);
    $json = json_decode($decode, true);
    $channel_id = random_code();
    $file = "../../conversation/" . $channel_id . ".json";

    while (file_exists($file)) {
        $channel_id = random_code();
        $file = "../../conversation/" . $channel_id . "/messages.json";
    }

    if($author == $target) {
        echo "Vous ?? Impossible de se demander soi même en ami !";
        exit;
    }

    if(array_key_exists($author, $json)) {
        if(array_key_exists($target, $json)) {
            if(!array_key_exists($target, $json[$author]["friends"])) {

                $json[$author]["friends"][$target] = [
                    'channel_id' => $channel_id,
                    'author' => "REQUESTED",
                    'status' => "PENDING"
                ];
                $json[$target]["friends"][$author] = [
                    'channel_id' => $channel_id,
                    'author' => "REQUESTER",
                    'status' => "PENDING",
                    'read' => False
                ];
        
                $newJson = json_encode($json, JSON_PRETTY_PRINT);

                // Écrire le contenu JSON modifié dans le fichier
                file_put_contents($FilePath, $newJson);

                echo "R OK";
            }
        } else {
            echo "\"$target\" n'existe pas ! Vous vous êtes sûrement trompé..";
            exit;
        }
    } else {
        echo "Quel est cette sorcellerie ? Vous n'existez pas ??";
    }

 } catch (Exception $e) {
    echo "Mince. Un problème est survenu. Ressayez plus tard !";
}
?>