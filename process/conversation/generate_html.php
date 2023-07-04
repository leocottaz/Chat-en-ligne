<?php 

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('errorHandler');

/**
 * Renvoie le code HTML de la liste d'amis
 */
function friend_list($json_file) {
    $json = getAllUsers($json_file);
    $user = getUser($_SESSION["username"], $json_file);
    $found = False;

    if (empty($user["friends"])) {
        echo "<li>Aucun ami trouvé</li>";
    } else {
        foreach ($user["friends"] as $friendName => $friendInfo) {
        
        $channel_id = $friendInfo["channel_id"];
        $status = $friendInfo["status"];

        if ($status === "OK") {
        
        $found = True;

        echo "<li class='friend'>
         <a class='friend_a' href='main.php?ch=$channel_id'>$friendName</a>
        </li>"; 
        }
    }

    if(!$found) {
        echo "<li class='friend'>
        <a class='friend_a' href='friend.php'>Aucun ami trouvé<br>Ajoutez en un !</a>
       </li>";
    }
    }
}

/**
 * Renvoie le code html de la partie tchat
 */

function tchat($json_file) {

    if (isset($_GET["ch"]) and !empty($_GET["ch"])) {
        $json = getAllUsers($json_file);
        $user = getUser($_SESSION["username"], $json_file);
        $exist = False;
        
        foreach ($user["friends"] as $friend) {
            if ($_GET["ch"] == $friend["channel_id"]) {
                $exist = True;
            }
        }

        $conversation_file_path = '../data/conversation/' . $_GET["ch"] . ".json";
        if ($exist == True and file_exists($conversation_file_path)) {
            
            $conversation_file = file_get_contents($conversation_file_path);
            $conversation = json_decode($conversation_file, true);
            
        } else {
                echo "Impossible d'acceder à la discussion";
                echo '<style> .top_bar { display: none; } </style>';
                echo '<style> .message_form_container { display: none; } </style>';
                exit;
        }
        
        foreach ($conversation["messages"] as $message) {
            $author = $message["author"];
            $status = $message["status"];
            $system = $message["system"];
            $id = $message["id"];
            $content = $message["content"];
            $read = $message["read"];

            if ($read) {
                if ($status !== "DELETED") {
                    if ($author == $_SESSION["username"]) { 
                        if($system) {
                            echo "
                            <div class='message user-message'>$content</div>
                            ";  
                        } else {
                            echo "
                            <div class='message user-message' messageId='$id'><button class='delete_button' onclick='DeleteMessage($id)'>Delete</button>" . htmlspecialchars($content) . "</div>
                            ";
                        }
                    } else {
                        if($system) {
                            echo "
                            <div class='message other-message'>$content</div>
                            ";  
                        } else {
                            echo "
                            <div class='message other-message' messageId='$id'>" . htmlspecialchars($content) . "</div>
                            ";
                        }
                    }
                }
            } else {
                if ($status !== "DELETED") {
                if ($author == $_SESSION["username"]) {
                    if($system) {
                        echo "
                        <div class='message user-message'>$content</div>
                        ";  
                    } else {
                        echo "
                        <div class='message user-message' messageId='$id'><button class='delete_button' onclick='DeleteMessage($id)'>Delete</button>" . htmlspecialchars($content) . "</div>
                        ";
                    }
                }
            }
            }
        }
    } else { //Aucun tchat ouvert par l'utilisateur
        echo "Aucun tchat d'ouvert";
        echo '<style> .message_form_container { display: none; } </style>';
        echo '<style> .top_bar { display: none; } </style>';
    }
}

function top_bar($json_file) {
    if (isset($_GET["ch"]) && !empty($_GET["ch"])) {
        try {
            $json = getAllUsers($json_file);
            $user = getUser($_SESSION["username"], $json_file);
            $Channel = $_GET["ch"];
            $ChannelFile = '../data/conversation/' . $Channel . ".json";
            $decode = file_get_contents($ChannelFile);
            $json = json_decode($decode, true);

            foreach ($json["header"]["user"] as $username => $status) {
                if ($username !== $_SESSION["username"]) {
                    echo "<h3>" . htmlspecialchars($username) . "</h3>";

                    $badge_color = $status[0] == "DISCONNECTED" ? "red" : "green";
                    echo "<div style='background-color: $badge_color;' class='connexion_badge'></div>";
                }
            }
        } catch (Exception $e) {
            // Gérer l'exception
        }
    }
}
?>