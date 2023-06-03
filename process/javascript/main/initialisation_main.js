var DeleteButtonDisplay = false;

// Fonction pour descendre dans le tchat le plus bas possible
function scrollToBottom() {
    var chatContainer = document.querySelector('.tchat');
    chatContainer.scrollTo({
      top: chatContainer.scrollHeight,
      behavior: 'smooth'
    });
}

function ButtonDeleteClicked() {
    const ButtonsDelete = document.querySelectorAll('.delete_button');

    if (DeleteButtonDisplay == false) {
      ButtonsDelete.forEach(function(button) {
      // Modifier le style de chaque bouton
      button.style.display = 'block';
      DeleteButtonDisplay = true;
    });
    } else {
      ButtonsDelete.forEach(function(button) {
      // Modifier le style de chaque bouton
      button.style.display = 'none';
      DeleteButtonDisplay = false;
    });
    
  };
}

// Titre en fonction de si l'onglet est actif ou non
document.addEventListener("visibilitychange", function() {
    if (document.visibilityState === "visible") {
      // L'utilisateur est actuellement sur la page
      document.title = "Shadwow's Chat";
    } else {
      QuitConversation();
      document.title = "Vous nous manquez :(";
    }
  });

// Dès que la page est chargée entièrement on focus l'input pour ne pas avoir à cliquer
// pour écrire un message et on met le tchat au plus bas possible
window.addEventListener('DOMContentLoaded', function() {
    RefreshConversation()
    const input = document.querySelector('.message_input');
    input.focus()
    scrollToBottom();
    
    // Associer l'appui sur la touche "Entrée" au bouton "Envoyer"
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            ContentInput();
        }
    });
});

// SI l'UTILISATEUR FERME SON NAVIGATEUR OU L'ONGLET ON LE DECONNECTE DE LA CONVERSATION
window.addEventListener("beforeunload", function(event) {
  QuitConversation();
  event.preventDefault(); // Cette ligne est facultative et peut être utilisée pour demander une confirmation à l'utilisateur avant la fermeture de la page
});

function ContentInput() {
  var input = document.querySelector('.message_input');
  var InputContent = input.value;

  if(InputContent == "/color") {
      RequestChangeWallpaper(); 
  } else {
      SendMessage();
  }
}

function RequestChangeWallpaper() {
  var input = document.querySelector('.message_input');
  element = "<div class='message user-message'> Choisissez la couleur souhaité : <input type='color' id='colorPicker' value='#141414'></input></div>"
  $('.tchat').append(element);
  input.value = ''
  const colorPicker = document.getElementById('colorPicker');
  colorPicker.addEventListener('change', function(event) {
      const selectedColor = event.target.value;
      console.log("Changement de couleur du tchat demandé");
      SendWallpaperModification(selectedColor)
  });
      
  scrollToBottom();
}

function darkenColor(Hex, percent) {
  // Convertir la couleur hexadécimale en valeurs RGB
  var r = parseInt(Hex.substring(1, 3), 16);
  var g = parseInt(Hex.substring(3, 5), 16);
  var b = parseInt(Hex.substring(5, 7), 16);

  // Calculer les nouvelles valeurs RGB plus foncées
  var percent = 100 - percent;
  r = Math.floor(r * (percent / 100));
  g = Math.floor(g * (percent / 100));
  b = Math.floor(b * (percent / 100));

  // Convertir les valeurs RGB en une nouvelle couleur hexadécimale
  var newColor = "#" +
    ("0" + r.toString(16)).slice(-2) +
    ("0" + g.toString(16)).slice(-2) +
    ("0" + b.toString(16)).slice(-2);

  return newColor;
}

function lightenColor(Hex, percent) {
  // Convertir la couleur hexadécimale en valeurs RGB
  var r = parseInt(Hex.substring(1, 3), 16);
  var g = parseInt(Hex.substring(3, 5), 16);
  var b = parseInt(Hex.substring(5, 7), 16);

  // Calculer les nouvelles valeurs RGB plus claires
  var percentBrighter = 100 + percent;
  r = Math.min(Math.floor(r * (percentBrighter / 100)), 255);
  g = Math.min(Math.floor(g * (percentBrighter / 100)), 255);
  b = Math.min(Math.floor(b * (percentBrighter / 100)), 255);

  // Convertir les valeurs RGB en une nouvelle couleur hexadécimale
  var newColor = "#" +
    ("0" + r.toString(16)).slice(-2) +
    ("0" + g.toString(16)).slice(-2) +
    ("0" + b.toString(16)).slice(-2);

  return newColor;
}

function hexToRgba(hex, opacity) {
  // Supprimer le caractère "#" s'il est présent
  hex = hex.replace("#", "");

  // Extraire les valeurs RGB du code hexadécimal
  const r = parseInt(hex.substring(0, 2), 16);
  const g = parseInt(hex.substring(2, 4), 16);
  const b = parseInt(hex.substring(4, 6), 16);

  // Vérifier et normaliser la valeur alpha fournie (entre 0 et 1)
  var final_opacity = Math.min(Math.max(opacity, 0), 1);

  // Retourner la couleur RGBA sous forme de chaîne de caractères
  return `rgba(${r}, ${g}, ${b}, ${final_opacity})`;
}

function ChangeWallpaper(color) {
  var top_bar = document.querySelector('.top_bar');
  var tchat = document.querySelector('.tchat');
  var message_form = document.querySelector('.message_form');
  var div_friend_list = document.querySelector('.div_friend_list');

  var verydarkenHex = darkenColor(color, 30);
  //var darkenHex = darkenColor(color, 10);
  //var lightenHex = lightenColor(color, 20);

  // On convertit l'entrée hexadécimale en rgba
  const verydarkenRgba = hexToRgba(verydarkenHex, 1)
  //const darkenRgba = hexToRgba(darkenHex, 0.7)
  const Rgba = hexToRgba(color, 1)
  //const lightenRgba = hexToRgba(lightenHex, 0.7)

  // On modifie la couleur des éléments
  top_bar.style.backgroundColor = verydarkenRgba;
  tchat.style.backgroundColor = Rgba;
  message_form.style.backgroundColor = verydarkenRgba;
  div_friend_list.style.backgroundColor = Rgba;
}

// On regarde si la quelque chose dans la conversation à été modifié toute les secondes 
// (message envoyé/supprimés, utilisateur sur la conversation ou non, couleur du tchat modifiés)
setInterval(RefreshConversation, 1000);

// TRAITEMENT DES REPONSES DE REQUETES AJAX

function PostRefreshConversation(response){
  response = response.split("<!!delimiter!!>")
  response.forEach(function(element) {
    if (element[0] == "R") {
      var result = element.substring(2);
      const message = document.querySelector('.message[messageId="'+ result +'"]');
      message.remove();
    } else if (element[0] == "S") {
      var status = document.querySelector('.connexion_badge');
      if (element[2] == "C") {
          status.style.backgroundColor = 'green';
      } else {
          status.style.backgroundColor = 'red';
      }
    } else if (element[0] == "C") {
      var color = element.substring(2);
      ChangeWallpaper(color);
    } else {
      $('.tchat').append(element);
      scrollToBottom();
    }
  });
}

