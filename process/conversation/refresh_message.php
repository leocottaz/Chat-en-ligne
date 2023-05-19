<?php
session_start();
if (!empty($_POST["ch"])) {
$Channel = $_POST["ch"];
$ChannelFile = '../../data/conversation/' . $Channel . ".json";
$decode = file_get_contents($ChannelFile);
$json = json_decode($decode, true);

foreach ($json["messages"] as &$message) {
    if ($message["author"] != $_SESSION["username"]) {
        if (!$message["read"]) {
            $message["read"] = True;
            echo "<div class='message other-message'>" . htmlspecialchars($message["content"]) . "</div>";
        }
    }
}

// Réencoder le tableau en JSON
$newJson = json_encode($json, JSON_PRETTY_PRINT);

// Écrire le contenu JSON modifié dans le fichier
file_put_contents($ChannelFile, $newJson);
}



?>