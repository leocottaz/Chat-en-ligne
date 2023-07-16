<?php
session_start();
function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorHandler');

try {
    $decode = file_get_contents("../../data/users.json");
    $json = json_decode($decode, true);
    $need_update = false;
    $user = $_SESSION["username"];
            
    foreach ($json[$user]["friends"] as $username => &$info) {
        $username = htmlspecialchars($username);

        if (!isset($info["author"])) {
            if($info["status"] == "ACCEPTED") {
                $need_update = true;
                $id = $info['channel_id'];
    
                echo "<!!delimiter!!>";
                echo "N $username";
                echo "<!!delimiter!!>";
                echo "A $id";
    
                $json[$user]["friends"][$username]["status"] = "OK";
            }
            continue;
        }

        if ($info["author"] == "REQUESTER") {
            if (!$info["read"]) {
                $need_update = True;
                $info["read"] = True;
                echo "<!!delimiter!!>";
                echo "<div class='friend_await_response' username='$username'>
                <span> $username </span> <div class='buttons'><button class='button accept_request' onclick='AcceptRequest(`$username`)'></button> <button class='button cancel_request' onclick='DeleteRequest(`$username`)'></button></div>
                </div>";
            }
        }

        if ($info["status"] == "DELETED") {
            unset($json[$user]["friends"][$username]);
            $need_update = true;
            echo "<!!delimiter!!>";
            echo "R $username";
        }
    }

    if ($need_update) {
        // Réencoder le tableau en JSON
        $newJson = json_encode($json, JSON_PRETTY_PRINT);

        // Écrire le contenu JSON modifié dans le fichier
        file_put_contents("../../data/users.json", $newJson);
    }

} catch (Exception $e) {
    http_response_code(500);
    exit;
}
?>