<?php
session_start();

define("STATUS_DISCONNECTED", "DISCONNECTED");
define("STATUS_CONNECTED", "CONNECTED");

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorHandler');

try {
    if (!empty($_POST["ch"])) {
        $Channel = $_POST["ch"];
        $ChannelFile = '../../data/conversation/' . $Channel . ".json";

        if (file_exists($ChannelFile)) {
            $decode = file_get_contents($ChannelFile);
            $json = json_decode($decode, true);
            $need_update = false;

            if ($json["header"]["user"][$_SESSION["username"]][0] == STATUS_DISCONNECTED) {
                $json["header"]["user"][$_SESSION["username"]][0] = STATUS_CONNECTED;
                $need_update = true;
            }

            foreach ($json["header"]["user"] as $username => $status) {
                if ($username !== $_SESSION["username"]) {
                    echo "S " . ($status[0] == STATUS_DISCONNECTED ? "D" : "C");
                }
            }

            echo "<!!delimiter!!>";

            // Envoyer la couleur dominante de la discussion
            $color = $json['header']['color'];
            echo "C $color";

            foreach ($json["messages"] as $key => &$message) {
                $Id = strval($message["id"]);

                if ($message["author"] != $_SESSION["username"]) {
                    if ($message["status"] == "DELETED") {
                        unset($json["messages"][$key]);
                        $need_update = true;
                        echo "<!!delimiter!!>";
                        echo "R $Id";
                    } else {
                        if (!$message["read"]) {
                            $need_update = true;
                            $message["read"] = true;
                            echo "<!!delimiter!!>";

                            if ($message["system"]) {
                                echo "<div class='message other-message' messageId='$Id'>" . $message["content"] . "</div>\n";
                            } else {
                                echo "<div class='message other-message' messageId='$Id'>" . htmlspecialchars($message["content"]) . "</div>\n";
                            }
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
    }
} catch (Exception $e) {
    http_response_code(500);
    exit;
}

?>