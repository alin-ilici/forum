$(document).ready(function()
{
    // private message
    $('#sendMeAPrivateMessage').on('click', function() {
        $('#newConversationModal').modal('show');
        $('#sendConversationButton').addClass('disabled');
    });
});