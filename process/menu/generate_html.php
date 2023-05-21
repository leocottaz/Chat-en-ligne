<?php 

/**
 * Renvoie le code html de la liste d'ami
 */

function friend_list($json_file) {
    
    $json = getAllUsers($json_file);
    $user = getUser($_SESSION["username"], $json_file);

    if (empty($user["friends"])) {
        echo "
        Aucun ami trouvé
        ";
        exit;
    }

    foreach ($user["friends"] as $friend) {
        $friend_id = $friend[1];
        $friend_name = $friend[0];
        $friend_conversation = $friend[2];
        $friend_conversation_text = "Discussion ouverte";

        if ($friend_conversation == False) {
            $friend_conversation_text = "Aucune discussion ouverte";
        }

        echo "
        <li class='friend'>
         <a href='main.php?ch=$friend_id' onclick='QuitConversation()'>$friend_name <br> $friend_conversation_text</a>
        </li>";
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
            if ($_GET["ch"] == $friend[1]) {
                $exist = True;
            }
        }


        // GESTION DE TOUTE LES ERREURS ET AVESTISSEMENTS DANS LE TRY {} D'APRES
        function errorHandler($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }
        set_error_handler('errorHandler');

        $conversation_file_path = '../data/conversation/' . $_GET["ch"] . ".json";
        if ($exist == True and file_exists($conversation_file_path)) {
            
            $conversation_file = file_get_contents($conversation_file_path);
            $conversation = json_decode($conversation_file, true);
            
        } else {
                echo "Impossible d'acceder à la discussion";
                exit;
        }
        
        foreach ($conversation["messages"] as $message) {
            $author = $message["author"];
            $id = $message["id"];
            $content = $message["content"];
            $read = $message["read"];

            if ($read) {
            if ($author == $_SESSION["username"]) {
                echo "
                <div class='message user-message' messageId='$id'><button class='delete_button' onclick='DeleteMessage($id)'>Delete</button>$content</div>
                ";
            } else {
                echo "
                <div class='message other-message' messageId='$id'>$content</div>
                ";
            }
        } else {
            if ($author == $_SESSION["username"]) {
                echo "
                <div class='message user-message' messageId='$id'><button class='delete_button' onclick='DeleteMessage($id)'>Delete</button>$content</div>
                ";
            }
        }
    }
    } else { //Aucun tchat ouvert par l'utilisateur
        echo "Aucun tchat d'ouvert";
        echo '<style> .message_form_container { display: none; } </style>';
    }
}

function top_bar($json_file) {
    if (isset($_GET["ch"]) and !empty($_GET["ch"])) {
        $json = getAllUsers($json_file);
        $user = getUser($_SESSION["username"], $json_file);
        $Channel = $_GET["ch"];
        $ChannelFile = '../data/conversation/' . $Channel . ".json";
        $decode = file_get_contents($ChannelFile);
        $json = json_decode($decode, true);

        foreach ($json["header"]["user"] as $username => $status) {
            if ($username !== $_SESSION["username"]) {
                echo "<h3>$username</h3>";
                if ($status[0] == "DISCONNECTED") {
                    echo "<div style='background-color: red;' class='connexion_badge'></div>";
                } else {
                    echo "<div style='background-color: green;' class='connexion_badge'></div>";
                }
            }
        }
    }
}
?>