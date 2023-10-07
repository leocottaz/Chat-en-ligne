<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shadwow Chat - Bienvenue</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="/style/login.css">
    <link rel="stylesheet" media="(max-width: 1440px)" href="/style/phone/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../process/javascript/login/initialisation_login.js"></script>
</head>

<body>
    <div id="register">
        <form action="/process/auth/redirect.php?a=register" method="POST">
            <h2 class="title">Créez vous un compte</h2>
            <?php
            if (isset($_SESSION['error']) && isset($_SESSION['input'])) {
                $error = $_SESSION['error'];
                $input = $_SESSION['input'];

                echo "<h2 class=\"error\">" . $error . "</h2>";
            }
            ?>
            <!-- Champ pour le nom d'utilisateur -->
            <input value="<?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : ""; ?>" maxlength="32"
                type="text" class="username" name="username" placeholder="Nom d'utilisateur"><br>
            <!-- Champ pour le mot de passe -->
            <input value="<?php echo (isset($_SESSION['password'])) ? $_SESSION['password'] : ""; ?>" maxlength="32"
                type="text" class="password" name="password" placeholder="Mot de passe"><br><br>
            <div class="checkbox-container">
                <label class="remember-text">
                    <input type="hidden" name="remember" value="0" />
                    <input class="checkbox" type="checkbox" name="remember" value="1" />
                    <span class="checkmark"></span>
                    <p>Retenir cet appareil pendant 30 jours.</p>
                </label>
            </div>

            <!-- Bouton pour soumettre le formulaire -->
            <input type="submit" class="submit" value="Créer le compte">
        </form>
        <!-- Un lien est ajouté pour se connecter si l'utilisateur a déjà un compte -->
        <p class="switch_method">Vous avez déjà un compte ? <a href="./login.php">Connectez vous !</a></p>
    </div>
    <?php
    if (isset($_SESSION['error']) && isset($_SESSION['input'])) {
        if ($input == 'all') {
            echo "<script>red_all_register()</script>";
        } elseif ($input == 'username') {
            echo "<script>red_username()</script>";
        } elseif ($input == 'password') {
            echo "<script>red_password()</script>";
        }
    }

    // Supprimez les variables de session une fois qu'elles ont été affichées
    unset($_SESSION['error']);
    unset($_SESSION['input']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    ?>
</body>

</html>