const params = new URLSearchParams(window.location.search);

function SendMessage() {
    const input = document.querySelector('.message_input');
    const messageContent = input.value;
    input.value = "";

    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/conversation/send_message.php',
        method: 'POST',
        data: { 'content': messageContent, 'ch': params.get('ch') },
        success: function(response) {
            // Traitement de la réponse réussie ici
            const tchatElement = document.querySelector('.tchat');
            tchatElement.insertAdjacentHTML('beforeend', response);
            scrollToBottom();
            console.log("Un message a été envoyé :\n" + messageContent);
        },
        error: function(xhr, status, error) {
            console.log(error);
            input.value = messageContent;
            const tchatElement = document.querySelector('.tchat');
            tchatElement.insertAdjacentHTML('beforeend', "<div class='message error-message'> Une erreur est survenue lors de l'envoi de votre message </div>");
            scrollToBottom();
        }
    });
}

function RefreshConversation() {
    if (params.has('ch') && document.visibilityState === "visible") {
        // Effectuez la requête Ajax
        $.ajax({
            url: '../process/conversation/refresh_conversation.php',
            method: 'POST',
            data: { 'ch': params.get('ch') },
            success: function(response) {
                PostRefreshConversation(response);
            },
            error: function(xhr, status, error) {
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