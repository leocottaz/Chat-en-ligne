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
    $author = $_SESSION["username"];
    $FilePath = "../../data/users.json";
    $decode = file_get_contents($FilePath);
    $json = json_decode($decode, true);
    $channel_id = random_code();
    $file = "../../conversation/" . $channel_id . ".json";

    while (file_exists($file)) {
        echo $channel_id . ".json existe déjà";
        $channel_id = random_code();
        $file = "../../conversation/" . $channel_id . ".json";
    }

    if($author == $target) {
        http_response_code(500);
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
            echo "T False";
        }
    }

 } catch (Exception $e) {
    echo "<div class='error-message'> Une erreur est survenue lors de la recherche de l'utilisateur </div>";
}










?>