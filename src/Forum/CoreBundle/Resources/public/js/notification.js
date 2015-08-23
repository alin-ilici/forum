$(document).ready(function(){
    poll();

    $('#notificationDropdown').on('click', function() {
        if ($('#notificationIcon').attr('src') == '/bundles/core/images/ios7-bell_best_new.png') {
            $.ajax({
                type: "post",
                url: Routing.generate('forum_core_notification_update_notifications', { 'forUserId': $('#userId').attr('data-value') }),
                dataType: "json",
                success: function(result) {
                },
                error: function(result) {
                }
            });

            $('#notificationIcon').attr('src', '/bundles/core/images/ios7-bell.png');
        }
    });
});

function poll() {
    if ($('#userId').length > 0) {
        $.ajax({
            type: "post",
            url: Routing.generate('forum_core_notification_check_for_new_notifications', { 'userId': $('#userId').attr('data-value') }),
            //url: "/bundles/core/php/checkForNewNotifications.php",
            async: true,
            data: { 'userId': $('#userId').attr('data-value') },
            dataType: "json",
            success: function(results) {
                if (Object.keys(results).length > 0) {
                    addNotification(results);
                }
                setTimeout(poll, 10000);
            },
            error: function(results) {
            }
        });
    }
}

function addNotification(results) {
    $('#noNewNotification').hide();
    $('#notificationIcon').attr('src', '/bundles/core/images/ios7-bell_best_new.png');
    if ($('#notificationDropdown .dropdown-menu').height() > 156) {
        $('#notificationDropdown .dropdown-menu').css('height', '191px');
        $('#notificationDropdown .dropdown-menu').css('overflow-y', 'scroll');
    }

    var notifications = '';

    results.forEach(function(data) {
        var conversationRoute = Routing.generate('forum_core_conversation_show_conversations', { conversationSlug: data['extraInfo']['conversationSlug'] });
        if (data['type'] == 'newPrivateMessage') {
            notifications = notifications +
                '<li>' +
                    '<a href="' + conversationRoute + '">' +
                        'You have a new private message from `' + data['extraInfo']['fromUsername'] + '`' +
                    '</a>' +
                '</li>';
        } else if (data['type'] == 'privateMessageResponse') {
            notifications = notifications +
                '<li>' +
                    '<a href="' + conversationRoute + '#' + data['extraInfo']['privateMessageId'] + '">' +
                        'New reply from `' + data['extraInfo']['fromUsername'] + '` in the conversation `' + data['extraInfo']['conversationName'] + '`' +
                    '</a>' +
                '</li>';
        }
    });

    notifications = notifications +
        '<li role="separator" class="divider"></li>' +
        '<li>' +
            '<a href="" id="seeAllNotifications">See all notifications</a>' +
        '</li>';

    $("#notificationDropdown .dropdown-menu").html(notifications);
}