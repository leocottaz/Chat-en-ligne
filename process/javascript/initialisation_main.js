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
    const input = document.querySelector('.message_input');
    input.focus()
    scrollToBottom();
    
    // Associer l'appui sur la touche "Entrée" au bouton "Envoyer"
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            SendMessage();
        }
    });
});

// SI l'UTILISATEUR FERME SON NAVIGATEUR OU L'ONGLET ON LE DECONNECTE DE LA CONVERSATION
window.addEventListener("beforeunload", function(event) {
  QuitConversation();
  event.preventDefault(); // Cette ligne est facultative et peut être utilisée pour demander une confirmation à l'utilisateur avant la fermeture de la page
});

// On regarde si de nouveaux messages sont en arrivés toute les secondes
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
    } else {
      $('.tchat').append(element);
      scrollToBottom();
    }
  });
}