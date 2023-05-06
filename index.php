<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shadwow's Chat</title>
  <link rel="stylesheet" href="/style/index.css">
</head>

<body>
  <nav>
    <?php
    include "./process/auth/authentification_process.php";

    if (isset($_COOKIE["user_id"])) {
      $cookie_result = request($_COOKIE["user_id"]);
      if ($cookie_result[0] == False) {
        echo '<a href="../login.php" class="login_button">Se connecter</a>';
      } else {
        echo '<a href="./user/main.php" class="access_chatspace_button">Acceder à mon espace</a>';
      }
    } else {
      echo '<a href="../login.php" class="login_button">Se connecter</a>';
    }
    ?>
  </nav>


  <div class="title">
    <p>Refaisons la communication.. Ensemble...</p>
    <p>Shadwow's Chat</p>
  </div>

</body>

</html>