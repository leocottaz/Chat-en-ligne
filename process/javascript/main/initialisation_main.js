var DeleteButtonDisplay = false;

// Fonction pour descendre dans le tchat le plus bas possible
function scrollToBottom() {
  document.querySelector('.tchat').scrollTo({
    top: document.querySelector('.tchat').scrollHeight,
    behavior: 'smooth'
  });
}

function ButtonDeleteClicked() {
  const ButtonsDelete = document.querySelectorAll('.delete_button');
  const newDisplayValue = DeleteButtonDisplay ? 'none' : 'block';

  ButtonsDelete.forEach(function(button) {
    // Modifier le style de chaque bouton
    button.style.display = newDisplayValue;
  });

  DeleteButtonDisplay = !DeleteButtonDisplay;
}

function handleVisibilityChange() {
  if (document.visibilityState === "visible") {
    // L'utilisateur est actuellement sur la page
    document.title = "Shadwow's Chat";
  } else {
    QuitConversation();
    document.title = "Vous nous manquez :(";
  }
}

// Dès que la page est chargée entièrement on focus l'input pour ne pas avoir à cliquer
// pour écrire un message et on met le tchat au plus bas possible
window.addEventListener('DOMContentLoaded', function() {
  RefreshConversation();
  const input = document.querySelector('.message_input');
  input.focus();
  scrollToBottom();

  input.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      ContentInput();
    }
  });
});

// SI l'UTILISATEUR FERME SON NAVIGATEUR OU L'ONGLET ON LE DECONNECTE DE LA CONVERSATION
window.addEventListener("beforeunload", function(event) {
  event.preventDefault()
  QuitConversation();
  event.returnValue = ""; // Cette ligne est facultative et peut être utilisée pour afficher un message personnalisé dans certains navigateurs plus anciens
});

function ContentInput() {
  var input = document.querySelector('.message_input');
  var InputContent = input.value;

  if (InputContent === "/color") {
    RequestChangeWallpaper();
  } else {
    SendMessage();
  }
}

function RequestChangeWallpaper() {
  var input = document.querySelector('.message_input');
  const colorPickerElement = document.createElement('input');
  colorPickerElement.type = 'color';
  colorPickerElement.id = 'colorPicker';
  colorPickerElement.value = '#141414';

  const messageElement = document.createElement('div');
  messageElement.classList.add('message', 'user-message');
  messageElement.textContent = 'Choisissez la couleur souhaitée : ';
  messageElement.appendChild(colorPickerElement);

  $('.tchat').append(messageElement);
  input.value = '';

  const colorPicker = document.getElementById('colorPicker');
  colorPicker.addEventListener('change', function(event) {
    const selectedColor = event.target.value;
    console.log("Changement de couleur du tchat demandé");
    SendWallpaperModification(selectedColor);
  });

  scrollToBottom();
}

function darkenColor(Hex, percent) {
  // Convertir la couleur hexadécimale en valeurs RGB
  var r = parseInt(Hex.substr(1, 2), 16);
  var g = parseInt(Hex.substr(3, 2), 16);
  var b = parseInt(Hex.substr(5, 2), 16);

  // Calculer les nouvelles valeurs RGB plus foncées
  var factor = 1 - (percent / 100);
  r = Math.floor(r * factor);
  g = Math.floor(g * factor);
  b = Math.floor(b * factor);

  // Convertir les valeurs RGB en une nouvelle couleur hexadécimale
  var newColor = "#" +
    ("00" + r.toString(16)).slice(-2) +
    ("00" + g.toString(16)).slice(-2) +
    ("00" + b.toString(16)).slice(-2);

  return newColor;
}


function lightenColor(Hex, percent) {
  // Convertir la couleur hexadécimale en valeurs RGB
  var r = parseInt(Hex.substr(1, 2), 16);
  var g = parseInt(Hex.substr(3, 2), 16);
  var b = parseInt(Hex.substr(5, 2), 16);

  // Calculer les nouvelles valeurs RGB plus claires
  var factor = 1 + (percent / 100);
  r = Math.min(Math.floor(r * factor), 255);
  g = Math.min(Math.floor(g * factor), 255);
  b = Math.min(Math.floor(b * factor), 255);

  // Convertir les valeurs RGB en une nouvelle couleur hexadécimale
  var newColor = "#" +
    ("00" + r.toString(16)).slice(-2) +
    ("00" + g.toString(16)).slice(-2) +
    ("00" + b.toString(16)).slice(-2);

  return newColor;
}

function hexToRgba(hex, opacity) {
  // Supprimer le caractère "#" s'il est présent
  hex = hex.replace("#", "");

  // Extraire les valeurs RGB du code hexadécimal
  const r = parseInt(hex.substr(0, 2), 16);
  const g = parseInt(hex.substr(2, 2), 16);
  const b = parseInt(hex.substr(4, 2), 16);

  // Vérifier et normaliser la valeur alpha fournie (entre 0 et 1)
  const finalOpacity = Math.min(Math.max(opacity, 0), 1);

  // Retourner la couleur RGBA sous forme de chaîne de caractères
  return `rgba(${r}, ${g}, ${b}, ${finalOpacity})`;
}

function ChangeWallpaper(color) {
  const top_bar = document.querySelector('.top_bar');
  const tchat = document.querySelector('.tchat');
  const message_form = document.querySelector('.message_form');
  const div_friend_list = document.querySelector('.div_friend_list');

  const verydarkenHex = darkenColor(color, 30);
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
setInterval(RefreshConversation, 500);

// TRAITEMENT DES REPONSES DE REQUETES AJAX

function PostRefreshConversation(response) {
  const elements = response.split("<!!delimiter!!>");

  for (const element of elements) {
    const type = element[0];
    const content = element.substring(2);

    if (type === "R") {
      const messageId = content;
      const message = document.querySelector(`.message[messageId="${messageId}"]`);
      if (message) {
        message.remove();
      }
    } else if (type === "S") {
      const status = document.querySelector('.connexion_badge');
      status.style.backgroundColor = content === "C" ? 'green' : 'red';
    } else if (type === "C") {
      ChangeWallpaper(content);
    } else {
      $('.tchat').append(element);
      scrollToBottom();
    }
  }
}


// LISTENER 
document.addEventListener("visibilitychange", handleVisibilityChange);