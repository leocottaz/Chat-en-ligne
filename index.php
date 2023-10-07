<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shadwow Chat</title>
  <link rel="shortcut icon" href="">
  <link rel="stylesheet" href="/style/index.css">
  <link rel="stylesheet" media="(max-width: 775px)" href="/style/phone/index.css">
</head>

<body>

<h1 class="title">Shadwow Chat</h1>
<p><span class="bigger">La communication..</span><br>
...est essentielle dans le monde d'aujourd'hui.<br>
 ...rapproche les gens, facilite les relations personnelles et professionnelles,<br>
  ...permet de partager des idées importantes à l'échelle mondiale.<br>
   Sans communication efficace, il serait difficile de construire un avenir meilleur...<br>
   <span class="bigger">..ensemble.</span><br>
</p>

<div class='button_div'>
  <?php
  include "./process/auth/verification.php";

  $connected = connected("./data/users.json");

  if ($connected[0] == False) {
    echo "
    <a class='button register' href=\"register.php\">
        S'inscrire
    </a>
    <a class='button login' href=\"login.php\">
        Se connecter
    </a>
    ";
  } else {
    echo "
    <a class='button login' href=\"./user/main.php\">
        Ouvrir Shadwow Chat
    </a>
    ";
  }
  ?>
</div>

</body>

</html>