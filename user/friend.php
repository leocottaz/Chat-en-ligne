<?php 
session_start();
include "../process/auth/verification.php";
include '../process/friend/generate_html.php';

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
  <title>Shadwow's Chat - Amis</title>
  <link rel="shortcut icon" href="">
  <link rel="stylesheet" href="../style/friend.css">
  <link rel="stylesheet" media="(max-width: 890px)" href="../style/phone/friend.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../process/javascript/friend/ajax.js"></script>
  <script src="../process/javascript/friend/initialisation_friend.js"></script>
</head>

<body>
  
<div class="container">

  <div class="div_friend_list">
  <a href="main.php"><button class="go_main_button"></button></a>
    <ul class="friend_list">
    <?php
    friend_list("../data/users.json");  
    ?>
    </ul>
  </div>

  

  <div class="friend_form_container">

    <div class="friend_form">
      <!-- Champ pour le message -->
      <input maxlength="32" type="text" class="friend_search_input" placeholder="Qui cherchez vous ?">
       <!-- Bouton pour envoyer le message -->
      <button class="submit" onclick="SearchFriend()">Rechercher</button>
    </div>

    <div class="display-warn">
      <h3 class="error-text"><h3>
    </div>

    <div class="friend_center_div">
      <div class="friend_await_response_div">
        <p class="title"> Vos demandes envoyées </p>
        <div class="horizontal_separator"></div>
        <?php
          friend_await_response("../data/users.json");
        ?>
      </div>
      <div class="vertical_separator"></div>
      <div class="friend_request">
        <p class="title"> Vos demandes reçues </p>
        <div class="horizontal_separator"></div>
        <?php
          friend_request("../data/users.json");
        ?>
      </div>
    </div>
  </div>
</div>

</body>
</html>