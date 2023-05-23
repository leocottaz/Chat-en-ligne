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
  <script src="../process/javascript/ajax.js"></script>
  <script src="../process/javascript/initialisation_main.js"></script>
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
    <div class='top_bar'>
      <?php
      top_bar("../data/users.json");  
      ?>
    </div>

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
    <button class="delete" onclick="ButtonDeleteClicked()"></button>
    </div>
  </div>
</div>
</div>

</body>
</html>