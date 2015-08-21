function addNotification(result) {
    $('#noNewNotification').hide();
    $('#notificationIcon').attr('src', '/bundles/core/images/ios7-bell_best_new.png');
    if ($('#notificationDropdown .dropdown-menu').height() > 156) {
        $('#notificationDropdown .dropdown-menu').css('height', '191px');
        $('#notificationDropdown .dropdown-menu').css('overflow-y', 'scroll');
    }
    $("#notificationDropdown .dropdown-menu").append(
        '<li><a href="#">You have a new personal message!</a></li>'
    );
}

function poll() {
    if ($('#userId').length > 0) {
        $.ajax({
            type: "post",
            url: Routing.generate('forum_core_notification_check_for_new_notifications', { 'userId': $('#userId').attr('data-value') }),
//            url: "/bundles/core/php/checkForNewNotifications.php",
            async: true,
            data: { 'userId': $('#userId').attr('data-value') },
            dataType: "json",
            success: function(result) {
                addNotification(result);
                setTimeout(poll, 5000);
            },
            error: function(result) {
            }
        });
    }
};

$(document).ready(function(){
//    poll();
});

//var socket = io();
//
//socket.emit('check_message', "aaa");
//
//socket.on('check_message', function(msg) {
//    addNotification();
//});
