<?php 
session_start();
include "../process/auth/verification.php";
include '../process/conversation/generate_html.php';

$connected = connected("../data/users.json");

if (!$connected[0]) {
  header("Location: ../register.php");
}
?> 

<!DOCTYPE html>
<html lang="fr">

 <head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bienvenue - Introduction</title>
   <link rel="shortcut icon" href="">
   <link rel="stylesheet" href="../style/start.css">
   <link rel="stylesheet" media="(max-width: 772px)" href="../style/phone/start.css">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="../process/javascript/start/script.js"></script>
 </head>

 <body>
  <div class="title-container">
  <h1 class="title">Bienvenue</h1>
  <p class="subtitle">Votre compte à été créé avec succès</p>
  <p class="sub-subtitle">Il est désormais temps de découvrir ce que vous pouvez faire !</p>
</div>
<br>
  <div class="container">

    <div class="top_bar" style="background-color: rgb(10, 10, 10);">
      <h3>Assistant</h3><div style="background-color: red;" class="connexion_badge"></div>      <img class="connexion_error_pic" src="http://localhost:3000/style/image/icon/connexion-error.svg" title="Vous n'êtes plus connecté à internet" style="display: none;">
    </div>

    <div class="tchat"></div>

    <div class="message_form_container">
      <div class="message_form">
        <textarea class="message_input" placeholder="Indisponible" disabled></textarea>
       <!-- Bouton pour envoyer le message -->
      <button class="submit" onclick="Message()" disabled>Envoyer</button>
      <button class="delete" onclick="ToggleDelete()" disabled></button>
    </div>
  </div>
</div>
    <a class='button' href="../user/friend.php">
      Ajouter de nouveaux amis
    </a>
    <a class='button' href="../user/main.php">
      Ouvrir Shadwow Chat
    </a>
 </body>
</html>