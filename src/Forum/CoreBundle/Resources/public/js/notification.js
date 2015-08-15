function addNotification(result) {
    $('#noNewNotification').hide();
    $('#newNotificationIcon').show();
    console.log($('#notificationDropdown .dropdown-menu').height());
    if ($('#notificationDropdown .dropdown-menu').height() > 156) {
        $('#notificationDropdown .dropdown-menu').css('height', '191px');
        $('#notificationDropdown .dropdown-menu').css('overflow-y', 'scroll');
    }
    $("#notificationDropdown .dropdown-menu").append(
        '<li><a href="#">You have a new personal message!</a></li>'
    );
}

function poll() {
    $.ajax({
        type: "post",
        url: Routing.generate('forum_core_notification_check_for_new_notifications', { 'userId': $('#userId').attr('data-value') }),
//        url: "/bundles/core/php/checkForNewNotifications.php",
        async: true,
        data: { 'userId': $('#userId').attr('data-value') },
        dataType: "json",
        success: function(result) {
            addNotification(result);
            setTimeout(poll, 5000);
        }
    });
};

$(document).ready(function(){
//    poll();
});