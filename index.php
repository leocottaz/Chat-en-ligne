<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shadwow's Chat</title>
  <link rel="shortcut icon" href="">
  <link rel="stylesheet" href="/style/index.css">
  <link rel="stylesheet" media="(max-width: 767px)" href="/style/phone/index.css">
</head>

<body>
  <nav>
<?php 
include "./process/auth/verification.php";

$connected = connected("./data/users.json");

if ($connected[0] == False) {
  echo "
  <a href=\"login.php\">
      Se connecter
  </a>
  ";
} else {
  echo "
  <a href=\"./user/main.php\">
      Accéder à mon espace
  </a>
  ";
}
?>  
</nav>

<div class="title">
<p>Refaisons la communication.. Ensemble...</p>
<p>Nom</p>
</div>

</body>

</html>