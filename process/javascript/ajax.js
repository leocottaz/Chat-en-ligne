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

function RefreshConversation() {
    if (params.has('ch')) {
        if (document.visibilityState === "visible") {
        // Effectuez la requête Ajax
        $.ajax({
            url: '../process/conversation/refresh_conversation.php',
            method: 'POST',
            data: {'ch' : params.get('ch')},
            success: function(response) {
                PostRefreshConversation(response);  
            },
            error: function(xhr, status, error) {
                console.log(error);
                $('.tchat').append("<div class='message error-message'> Une erreur est survenue lors de la réception d'un message </div>");
                scrollToBottom();
            }
            });
          }
        
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

function QuitConversation() {
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/conversation/quit_conversation.php',
        method: 'POST',
        data: { 'ch' : params.get('ch') }
    });
}