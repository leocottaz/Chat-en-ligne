const params = new URLSearchParams(window.location.search);

function SendMessage() {
    const input = document.querySelector('.message_input');
    const messageContent = input.value;

    if (messageContent.length === 0) {
        return false;
    };


    const tchatElement = document.querySelector('.tchat');
    tchatElement.insertAdjacentHTML('beforeend', "<div class='message sending user-message'>" + messageContent + `   <img class='sending_pic' src="" title="Envoi du message en cours"></div>`);
    var elementsImages = document.querySelectorAll('.sending_pic');

    // Parcourir tous les éléments et appliquer les modifications
    elementsImages.forEach(function(elementImage) {
        // Modifier la source de l'image
        elementImage.src = MessageSendingSVG.src;
    });
    scrollToBottom();
    input.value = "";

    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/conversation/send_message.php',
        method: 'POST',
        data: { 'content': messageContent, 'ch': params.get('ch') },
        success: function(response) {
            // Traitement de la réponse réussie ici
            const elementASupprimer = tchatElement.querySelector('.message.sending.user-message');
            // Vérifiez si l'élément existe avant de le supprimer
            if (elementASupprimer) {
                // Utilisez parentNode.removeChild() pour supprimer l'élément
                tchatElement.removeChild(elementASupprimer);
            }
            tchatElement.insertAdjacentHTML('beforeend', response);
            scrollToBottom();
            console.log("Un message a été envoyé :\n" + messageContent);
        },
        error: function(xhr, status, error) {
            console.log(error);
            // Traitement de la réponse réussie ici
            const elementASupprimer = tchatElement.querySelector('.message.sending.user-message');
            // Vérifiez si l'élément existe avant de le supprimer
            if (elementASupprimer) {
                // Utilisez parentNode.removeChild() pour supprimer l'élément
                tchatElement.removeChild(elementASupprimer);
            }
            if (xhr.status === 400) {
                return false;
            };
            tchatElement.insertAdjacentHTML('beforeend', `<div class='message user-message'>`
            + encodeURI(messageContent) +
            ` <img class='error_sending_pic' src="" title="Une erreur est survenue lors de l'envoi du message" alt="Une erreur est survenue lors de l'envoi du message">
            </div>`);
            var elementsImages = document.querySelectorAll('.error_sending_pic');

            // Parcourir tous les éléments et appliquer les modifications
            elementsImages.forEach(function(elementImage) {
                // Modifier la source de l'image
                elementImage.src = MessageErrorSVG.src;
            });
            scrollToBottom();
        }
    });
}

function RefreshConversation() {
    const connexion_error_pic = document.querySelector('.connexion_error_pic');
    const server_error_pic = document.querySelector('.server_error_pic');
    if (navigator.onLine) {
        connexion_error_pic.style.display = "none";
    } else {
        server_error_pic.style.display = "none";
        connexion_error_pic.style.display = "block";
        return false;
    }
    if (params.has('ch') && document.visibilityState === "visible") {
        // Effectuez la requête Ajax
        $.ajax({
            url: '../process/conversation/refresh_conversation.php',
            method: 'POST',
            data: { 'ch': params.get('ch') },
            success: function(response) {
                server_error_pic.style.display = "none";
                PostRefreshConversation(response);
            },
            error: function(xhr, status, error) {
                server_error_pic.style.display = "block";
                connexion_error_pic.style.display = "none";
                console.log(error);
            }
        });
    }
}

function DeleteMessage(MessageId) {
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/conversation/delete_message.php',
        method: 'POST',
        data: { 'ch': params.get('ch'), 'id': MessageId },
        success: function(response) {
            const message = document.querySelector('.message[messageId="' + MessageId + '"]');
            if (message) {
                message.remove();
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('.tchat').append("<div class='message error-message'> Une erreur est survenue lors de la suppression d'un message </div>");
            scrollToBottom();
        }
    });
}

function SendWallpaperModification(color) {
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/conversation/change_wallpaper.php',
        method: 'POST',
        data: { 'ch': params.get('ch'), 'color': color },
        success: function(response) {
            response = response.split("<!!delimiter!!>");
            response.forEach((element) => {
                if (element.startsWith("#")) {
                    ChangeWallpaper(element);
                } else {
                    $('.tchat')[0].insertAdjacentHTML('beforeend', element);
                }
            });
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('.tchat').append("<div class='message error-message'> Nous n'avons pas réussi à changer la couleur du tchat </div>");
            scrollToBottom();
        }
    });
}

function QuitConversation() {
    // Effectuez la requête Ajax
    $.ajax({
      url: '../process/conversation/quit_conversation.php',
      method: 'POST',
      data: { 'ch' : params.get('ch') }
    });
  }