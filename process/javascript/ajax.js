const params = new URLSearchParams(window.location.search);

function SendMessage() {
    var input = document.querySelector('.message_input');
    var MessageContent = input.value;
    
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/conversation/send_message.php',
        method: 'POST',
        data: { 'content': MessageContent, 'ch' : params.get('ch')},
        success: function(response) {
            // Traitement de la réponse réussie ici
            $('.tchat').append(response);
            var chatContainer = document.querySelector('.tchat');
            scrollToBottom();
            input.value = "";
            console.log("Un message à été envoyé : \n" + MessageContent);
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('.tchat').append("<div class='message error-message'> Une erreur est survenue lors de l'envoi de votre message </div>");
            scrollToBottom();
        }
    });
}

function RefreshMessage() {
    if (params.has('ch')) {
        // Effectuez la requête Ajax
        $.ajax({
            url: '../process/conversation/refresh_message.php',
            method: 'POST',
            data: {'ch' : params.get('ch')},
            success: function(response) {
                if (response.trim().length !== 0) {
                    $('.tchat').append(response);
                    scrollToBottom();
                } 
            },
            error: function(xhr, status, error) {
                console.log(error);
                $('.tchat').append("<div class='message error-message'> Une erreur est survenue lors de la réception d'un message </div>");
                scrollToBottom();
            }
        });
    }
}

function DeleteMessage(MessageId) {
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/conversation/delete_message.php',
        method: 'POST',
        data: { 'ch' : params.get('ch'), 'id': MessageId },
        success: function(response) {
            const message = document.querySelector('.message[messageId="'+ MessageId +'"]');
            message.remove();
        },
        error: function(xhr, status, error) {
            console.log(error);
            $('.tchat').append("<div class='message error-message'> Une erreur est survenue lors de la suppression d'un message </div>");
            scrollToBottom();
        }
    });
}