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
            PostSearchFriend(response)
        },
        error: function(xhr, status, error) {
            $('.friend_form_container').append("<div class='error-message'> La recherche n'a pas abouti. </div>");
        }
    });
}