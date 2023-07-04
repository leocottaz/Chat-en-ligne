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
        echo "<li>Aucun ami trouvé</li>";
    }
    }

    
}

function friend_await_response($json_file) {
    $json = getAllUsers($json_file);
    $user = getUser($_SESSION["username"], $json_file);

    foreach ($user["friends"] as $friendName => $friendInfo) {

        $status = $friendInfo["status"];

        if ($status == "PENDING") {
            $author = $friendInfo["author"];

            if ($author == "REQUESTED") {
                echo "<div class='friend_await_response' username='$friendName'>
                <span>$friendName</span> <div class='buttons'> <button class='button cancel_request' onclick='DeleteRequest(`$friendName`)'></button></div>
                </div>";
            }
        }  
    }
}

function friend_request($json_file) {
    $json = getAllUsers($json_file);
    $user = getUser($_SESSION["username"], $json_file);

    foreach ($user["friends"] as $friendName => $friendInfo) {
        
        $status = $friendInfo["status"];
        if ($status == "PENDING") {
            $author = $friendInfo["author"];

            if(isset($friendInfo["read"]) and $friendInfo["read"] == True) {
                if ($author == "REQUESTER") {
                echo "<div class='friend_await_response' username='$friendName'>
                <span>$friendName</span> <div class='buttons'><button class='button accept_request' onclick='AcceptRequest(`$friendName`)'></button> <button class='button cancel_request' onclick='DeleteRequest(`$friendName`)'></button></div>
                </div>";
                }
            }
        }
    }
}
?>