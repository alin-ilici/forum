$(document).ready(function($) {
    console.log('m-am incarcat');

    // private message
    $('.sendMeAMessage').on('click', function() {
        $('#newConversationModal').modal('show');
        $('#toUser').val($(this).attr('data-value'));
        $('#toUser').attr('readonly', 'true');
    });
});