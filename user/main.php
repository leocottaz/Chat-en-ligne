<?php 
session_start();
include "../process/auth/verification.php";

$connected = connected("../data/users.json");

if ($connected[0] == False) {
  header("Location: ../login.php");
}
?> 

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shadwow's Chat</title>
  <link rel="shortcut icon" href="">
  <link rel="stylesheet" href="../style/main.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  
<div class="container">
  <div class="div_friend_list">
    <ul class="friend_list">
    <?php
    include '../process/menu/generate_html.php';
    friend_list("../data/users.json");  
    ?>
    </ul>
  </div>

  <div class="container_tchat">
  <div class="tchat">
  <?php
    tchat("../data/users.json");  
    ?>
  </div>

  <div class="message_form_container">
    <div class="message_form">
      <!-- Champ pour le message -->
      <input type="text" class="message_input" placeholder="Tapez votre message ici">
       <!-- Bouton pour envoyer le message -->
    <button class="submit" onclick="SendMessage()">Envoyer</button>
    </div>
  </div>
</div>
</div>

<script>

document.addEventListener("visibilitychange", function() {
  if (document.visibilityState === "visible") {
    // L'utilisateur est actuellement sur la page
    document.title = "Revenez";
  } else {
    // L'utilisateur est actuellement sur un autre onglet ou une autre fenêtre
    document.title = "Shadwow's Chat";
  }
});

function scrollToBottom() {
  var chatContainer = document.querySelector('.tchat');
  chatContainer.scrollTo({
    top: chatContainer.scrollHeight,
    behavior: 'smooth'
  });
}

var input = document.querySelector('.message_input');

  // Associer l'appui sur la touche "Entrée" à l'action
input.addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
    SendMessage();
  }
});

  window.addEventListener('DOMContentLoaded', function() {
    input.focus()
    scrollToBottom();
  });
</script>

<script>
function SendMessage() {

var input = document.querySelector('.message_input');
var MessageContent = input.value;
const params = new URLSearchParams(window.location.search);

// Effectuez la requête Ajax
$.ajax({
        url: '../process/conversation/send_message.php',
        method: 'POST',
        data: { 'content': MessageContent, 'ch' : params.get('ch')},
        success: function(response) {
          // Traitement de la réponse réussie ici
          $('.tchat').append(response);
          var chatContainer = document.querySelector('.tchat');
          scrollToBottom();
          input.value = "";
          console.log("Un message à été envoyé : \n" + MessageContent);
        },
        error: function(xhr, status, error) {
          console.log(error);
          $('.tchat').append("<div class='message error-message'> Une erreur est survenue lors de l'envoi de votre message </div>");
          scrollToBottom();
        }
      });
}

const params = new URLSearchParams(window.location.search);

function RefreshMessage() {

if (params.has('ch')) {
  // Effectuez la requête Ajax
$.ajax({
        url: '../process/conversation/refresh_message.php',
        method: 'POST',
        data: {'ch' : params.get('ch')},
        success: function(response) {
          $('.tchat').append(response);
          scrollToBottom();
        },
        error: function(xhr, status, error) {
          console.log(error);
          $('.tchat').append("<div class='message error-message'> Une erreur est survenue lors de la réception d'un message </div>");
          scrollToBottom();
        }
      });
}

}
setInterval(RefreshMessage, 1000);
</script>

</body>
</html>