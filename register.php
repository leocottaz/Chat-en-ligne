<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shadwow Chat - Bienvenue</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="/style/login.css">
    <link rel="stylesheet" media="(max-width: 1180px)" href="/style/phone/login.css">
</head>

<body>
<div id="register">
<form action="/process/auth/redirect.php?a=register" method="POST">
            <h2 class="title">Créez vous un compte</h2>
            <!-- Champ pour le nom d'utilisateur -->
            <input maxlength="32" type="text" class="username" name="username" placeholder="Nom d'utilisateur"><br>
            <!-- Champ pour le mot de passe -->
            <input maxlength="32" type="password" class="password" name="password" placeholder="Mot de passe"><br><br>
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
</body>
</html>