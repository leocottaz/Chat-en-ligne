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
    
    if (!isset($json[$user]["friends"][$target]) or !isset($json[$target]["friends"][$user])) {
        http_response_code(500);
        exit;
    }

    $UserAuthor = $json[$user]["friends"][$target]["author"];
    $UserStatus = &$json[$user]["friends"][$target]["status"];
    $FriendStatus = &$json[$target]["friends"][$user]["status"];
    $IDChannel = $json[$user]["friends"][$target]["channel_id"];

    if ($UserAuthor == "REQUESTER") {
        unset($json[$user]["friends"][$target]["author"]);
        unset($json[$user]["friends"][$target]["read"]);
        unset($json[$target]["friends"][$user]["author"]);
        $UserStatus = "OK";
        $FriendStatus = "ACCEPTED";

        echo $IDChannel;

        $data = array(
            'header' => array(
                'color' => '',
                'user' => array(
                    $user => array(
                        'DISCONNECTED'
                    ),
                    $target => array(
                        'DISCONNECTED'
                    )
                )
            ),
            'messages' => array()
        );

        $jsonData = json_encode($data);

        $filename =  $json[$user]["friends"][$target]["channel_id"] . ".json";
        $file = fopen("../../data/conversation/" . $filename, 'w');

        if ($file) {
            fwrite($file, $jsonData);
            fclose($file);
        } else {
            http_response_code(500);
            exit;
        }













    } else {
        http_response_code(500);
        exit;
    }

    $newJson = json_encode($json, JSON_PRETTY_PRINT);

    // Écrire le contenu JSON modifié dans le fichier
    file_put_contents("../../data/users.json", $newJson);

} catch (Exception $e) {
    http_response_code(500);
    exit;
}

?>