const params = new URLSearchParams(window.location.search);

function SearchFriend() {
    var input = document.querySelector('.friend_search_input');
    var InputContent = input.value;
    
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/friend/search_friend.php',
        method: 'POST',
        data: { 'target': InputContent},
        success: function(response) {
            PostSearchFriend(response, InputContent)
        },
        error: function(xhr, status, error) {
            
        }
    });
}

function RefreshRequest() {
    if (document.visibilityState === "visible") {
        // Effectuez la requête Ajax
        $.ajax({
            url: '../process/friend/refresh_request.php',
            method: 'POST',
            data: {},
            success: function(response) {
                console.log(response)
                PostRefreshRequest(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }
}

function DeleteRequest(username) {
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/friend/delete_request.php',
        method: 'POST',
        data: { 'user': username },
        success: function(response) {
            const Friend = document.querySelector(`.friend_await_response[username="${username}"]`);
            if (Friend) {
              Friend.remove();
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}

function AcceptRequest(username) {
    // Effectuez la requête Ajax
    $.ajax({
        url: '../process/friend/accept_request.php',
        method: 'POST',
        data: { 'user': username },
        success: function(response) {
            const Friend = document.querySelector(`.friend_await_response[username="${username}"]`);
            if (Friend) {
              Friend.remove();
            }
            $(".friend_list").append(`<li class='friend'>
            <a class='friend_a' href='main.php?ch=${response}'>${username}</a>
           </li>`)
            
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}