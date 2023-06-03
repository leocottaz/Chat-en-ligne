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

function PostSearchFriend(response){
    console.log(response)
}