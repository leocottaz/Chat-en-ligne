<?php 
session_start();
include "../process/auth/verification.php";
include '../process/conversation/generate_html.php';

$connected = connected("../data/users.json");

if (!$connected[0]) {
  header("Location: ../login.php");
}
?> 

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vos Tchats</title>
  <link rel="shortcut icon" href="">
  <link rel="stylesheet" href="../style/main.css">
  <link rel="stylesheet" media="(max-width: 767px)" href="../style/phone/main.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../process/javascript/main/ajax.js"></script>
  <script src="../process/javascript/main/initialisation_main.js"></script>
</head>

<body>
  
<div class="container">
  <div class="div_friend_list">
  <a href="friend.php"><button class="search_friend_button"></button></a>
    <ul class="friend_list">
    <?php
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
      <textarea class="message_input" placeholder="Tapez votre message ici"></textarea>
       <!-- Bouton pour envoyer le message -->
    <button class="submit" onclick="ContentInput()">Envoyer</button>
    <button class="delete" onclick="ButtonDeleteClicked()"></button>
    </div>
  </div>
</div>
</div>

</body>
</html>