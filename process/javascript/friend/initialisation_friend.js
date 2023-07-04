// Dès que la page est chargée entièrement on focus l'input pour ne pas avoir à cliquer
// pour rechercher un ami
window.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('.friend_search_input');
    input.focus()
    
    // Associer l'appui sur la touche "Entrée" au bouton "Envoyer"
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            SearchFriend();
        }
    });
});

// TRAITEMENT DES REPONSES DE REQUETES AJAX

function PostSearchFriend(response, target){
    element = `<div class='friend_await_response' username='${target}'>
    <span>${target}</span> <div class='buttons'> <button class='button cancel_request' onclick='DeleteRequest("${target}")'></button></div>
    </div>`;

    if (response === "R OK") {
        $('.friend_await_response_div').append(element);
    };
}

function PostRefreshRequest(response) {
    const elements = response.split("<!!delimiter!!>");
  
    for (const element of elements) {
      const type = element[0];
      const content = element.substring(2);
  
      if (type === "R") {
        const FriendUsername = content;
        const Friend = document.querySelector(`.friend_await_response[username="${FriendUsername}"]`);
        if (Friend) {
          Friend.remove();
        }
     } else if(type === "N") { // Nom d'utilisateur
        var Username = content;
      } else if(type === "A") { // Accept
        var ChannelId = content;
        const Friend = document.querySelector(`.friend_await_response[username="${Username}"]`);
        if (Friend) {
          Friend.remove();
        }
        $(".friend_list").append(`<li class='friend'>
            <a class='friend_a' href='main.php?ch=${ChannelId}'>${Username}</a>
           </li>`)
      } else {
        $('.friend_request').append(element);
      }
    }
  }

  setInterval(RefreshRequest, 500);