<?php
session_start();

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('errorHandler');

try {
    $MessageAuthor = $_SESSION["username"];
    $MessageContent = $_POST["content"];
    $Channel = $_POST["ch"];
    $ChannelFile = '../../data/conversation/' . $Channel . "/messages.json";

    $json = json_decode(file_get_contents($ChannelFile), true);
    $MessageId = count($json["messages"]) + 1;

    $MessageContent = rtrim($MessageContent, " \t\n\r\0\x0B");

    if (empty($MessageContent)) {
        http_response_code(400);
        exit;
    }

    $Message = [
        "author" => $MessageAuthor,
        "id" => $MessageId,
        "read" => false,
        "status" => "OK",
        "system" => false,
        "date" => date('Y-m-d\TH:i'),
        "content" => $MessageContent
    ];

    $json['messages'][] = $Message;

    file_put_contents($ChannelFile, json_encode($json, JSON_PRETTY_PRINT));

    echo "<div class='message user-message' messageId='$MessageId'>
            <button class='delete_button' onclick='DeleteMessage($MessageId)'>Delete</button>
            <p>" . htmlspecialchars($MessageContent) . "</p>
        </div>";

} catch (Exception $e) {
    http_response_code(500);
    exit;
}
?>