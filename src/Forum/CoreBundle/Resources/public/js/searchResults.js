$(document).ready(function($) {
    // private message
    $('.sendMeAMessage').on('click', function() {
        $('#newConversationModal').modal('show');
        $('#toUser').val($(this).attr('data-value'));
        $('#toUser').attr('readonly', 'true');
    });

    $('body').on('click', '.banUser', function() {
        var userId = $(this).attr('data-value');

        $.ajax({
            type: 'post',
            url: Routing.generate('forum_core_user_ban_user', { 'userId': userId }),
            dataType: 'json',
            success: function(result) {
                if (result == 'success') {
                    location.reload();
                } else if (result == 'fail') {
                    alert("An error occured!");
                }
            },
            fail: function(result) {
                alert("An error occured!");
            }
        });
    });
});