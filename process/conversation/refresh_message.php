<?php
session_start();
if (!empty($_POST["ch"])) {
$Channel = $_POST["ch"];
$ChannelFile = '../../data/conversation/' . $Channel . ".json";
$decode = file_get_contents($ChannelFile);
$json = json_decode($decode, true);
$need_update = False;

foreach ($json["messages"] as $key => &$message) {
    $Id = strval($message["id"]);
    if ($message["author"] != $_SESSION["username"]) {
        if ($message["status"] == "DELETED") {
            unset($json["messages"][$key]);
            $need_update = True;
            echo "R $Id";
        } else {
            if (!$message["read"]) {
                $need_update = True;
                $message["read"] = True;
                echo "<div class='message other-message' messageId='$Id'>" . htmlspecialchars($message["content"]) . "</div>";
            }
        }
    }  
}

if ($need_update) {
// Réencoder le tableau en JSON
$newJson = json_encode($json, JSON_PRETTY_PRINT);

// Écrire le contenu JSON modifié dans le fichier
file_put_contents($ChannelFile, $newJson);
}
}



?>