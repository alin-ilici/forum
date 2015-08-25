$(document).ready(function()
{
    var rowToDelete;
    var messageIdFileToDelete;
    var whatTable;

    $('#changeAvatarModal').on('hidden.bs.modal', function(event)
    {
        $("#avatar").val('');
        $('#newAvatar').hide();
        $('#errorMessages').hide();
        $('#submitChangeAvatarForm').addClass('disabled');
    });

    $('#changePasswordModal').on('hidden.bs.modal', function(event)
    {
        var modalBody = $(this).find('.modal-body');
        modalBody.empty();
        modalBody.append(
            '<div class="requiredInfo">' +
                '* Required information' +
            '</div>' +
            '<br/>' +
            '<div class="form-group">' +
                '<label for="userOldPassword" class="required">Old password</label>' +
                '<input type="password" id="userOldPassword" required="required" class="form-control" autocomplete="off" aria-describedby="inputOldPass">' +
            '</div>' +
            '<div class="form-group">' +
                '<label for="userNewPassword" class="required">New password</label>' +
                '<input type="password" id="userNewPassword" name="newPassword" required="required" class="form-control" autocomplete="off" aria-describedby="inputNewPass">' +
            '</div>' +
            '<div class="form-group">' +
                '<label for="userNewPasswordVerification" class="required">Retype new password</label>' +
                '<input type="password" id="userNewPasswordVerification" required="required" class="form-control" autocomplete="off" aria-describedby="inputNewPassVerStatus">' +
            '</div>'
        );

        $('#submitChangePassword').addClass('disabled');
    });

    $('#removeAvatar').on('click', function() {
        $('#removeAvatarModal').modal('show');
    });

    $('#changeAvatar').on('click', function() {
        $('#changeAvatarModal').modal('show');
    });

    $('#yesRemoveAvatarButton').on('click', function() {
        $.ajax({
            type: 'post',
            url: Routing.generate('forum_core_profile_page_change_or_remove_avatar', { username: $('#actualUsername').text().trim() }),
            dataType: 'json',
            success: function(result) {
                if (result == 'success') {
                    window.location.replace(window.location.href);
                }
            }
        });
    });

    $("#avatar").on('change', function(event) {
        if (this.disabled)
            return alert('File upload not supported!');
        var F = this.files;
        if (F && F[0]) {
            for (var i = 0; i < F.length; i++) {
                readImage(F[i]);
            }
        } else {
            $('#newAvatar').hide();
            $('#submitChangeAvatarForm').addClass('disabled');
        }
    });

    $('#changePassword').on('click', function() {
        $('#changePasswordModal').modal('show');
    });

    $(document).on('blur', '#userOldPassword', function() {
        var parent = $('#userOldPassword').parent();

        if ($(this).val() != '') {
            $.ajax({
                type: 'post',
                url: Routing.generate('forum_core_register_check_for'),
                data: {
                    what: 'password',
                    whatValue: $(this).val(),
                    username: $('#actualUsername').text().trim()
                },
                dataType: 'json',
                success: function(result) {
                    var parent = $('#userOldPassword').parent();

                    if (result == 'success') {
                        parent.removeClass();
                        parent.addClass("form-group has-success has-feedback");
                        parent.find('span').remove();
                        parent.append(
                            '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>' +
                            '<span id="inputOldPass" class="sr-only">(success)</span>'
                        );
                        $('#inputOldPassMessage').remove();
                        activateChangePasswordButtonIfPossible();
                    } else {
                        parent.removeClass();
                        parent.addClass("form-group has-error has-feedback");
                        parent.find('span').remove();
                        parent.append(
                            '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                            '<span id="inputOldPass" class="sr-only">(error)</span>'
                        );
                        $('#inputOldPassMessage').remove();
                        parent.after('<div id="inputOldPassMessage" class="alert alert-danger" role="alert">Wrong old password!</div>');
                        $('#submitChangePassword').addClass('disabled');
                    }
                }
            });
        } else {
            parent.removeClass();
            parent.addClass("form-group has-error has-feedback");
            parent.find('span').remove();
            parent.append(
                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                '<span id="inputOldPass" class="sr-only">(error)</span>'
            );
            $('#inputOldPassMessage').remove();
            parent.after('<div id="inputOldPassMessage" class="alert alert-danger" role="alert">The field can not be empty!</div>');
            $('#submitChangePassword').addClass('disabled');
        }
    });

    $(document).on('blur', '#userNewPasswordVerification, #userNewPassword', function() {
        var realPassword = $('#userNewPassword').val();
        var parent = $('#userNewPasswordVerification').parent();

        if ($('#userNewPasswordVerification').val() != '' &&
            $('#userNewPassword').val() != '') {
            if (realPassword != $('#userNewPasswordVerification').val()) {
                parent.removeClass();
                parent.addClass("form-group has-error has-feedback");
                parent.find('span').remove();
                parent.append(
                    '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                    '<span id="inputPassVerStatus" class="sr-only">(error)</span>'
                );
                $('#inputRetypePasswordVerMessage').remove();
                parent.after('<div id="inputRetypePasswordVerMessage" class="alert alert-danger" role="alert">The passwords do not match!</div>');
                $('#submitChangePassword').addClass('disabled');
            } else {
                parent.removeClass();
                parent.addClass("form-group has-success has-feedback");
                parent.find('span').remove();
                parent.append(
                    '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>' +
                    '<span id="inputPassVerStatus" class="sr-only">(success)</span>'
                );
                $('#inputRetypePasswordVerMessage').remove();
                activateChangePasswordButtonIfPossible();
            }
        } else if (($('#userNewPasswordVerification').val() == '' && $('#userNewPassword').val() != '') ||
            ($('#userNewPasswordVerification').val() == '' && $('#userNewPassword').val() == '')) {
            parent.removeClass();
            parent.addClass('form-group');
            parent.find('span').remove();
            $('#inputRetypePasswordVerMessage').remove();
            $('#submitChangePassword').addClass('disabled');
        } else if ($('#userNewPasswordVerification').val() != '' &&
            $('#userNewPassword').val() == '') {
            parent.removeClass();
            parent.addClass("form-group has-error has-feedback");
            parent.find('span').remove();
            parent.append(
                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                '<span id="inputPassVerStatus" class="sr-only">(error)</span>'
            );
            $('#inputRetypePasswordVerMessage').remove();
            parent.after('<div id="inputRetypePasswordVerMessage" class="alert alert-danger" role="alert">The passwords do not match!</div>');
            $('#submitChangePassword').addClass('disabled');
        }
    });

    $('body').on('click', '.removeMessageFile', function() {
        rowToDelete = $(this).parent().parent();
        messageIdFileToDelete = $(this).val();
        if ($(this).attr('what-table') == 'message') {
            whatTable = 'message';
        } else if ($(this).attr('what-table') == 'privateMessage') {
            whatTable = 'privateMessage';
        }
        $('#deleteFileModal').modal('show');
    });

    $('body').on('click', '#yesDeleteFileButton', function() {
        var route;
        if (whatTable == 'message') {
            route = Routing.generate('forum_core_message_delete_message_file', { 'messageId': messageIdFileToDelete });
        } else if (whatTable == 'privateMessage') {
            route = Routing.generate('forum_core_private_message_delete_private_message_file', { 'privateMessageId': messageIdFileToDelete });
        }

        $.ajax({
            type: 'post',
            url: route,
            dataType: 'json',
            success: function(result) {
                if (result == 'success') {
                    rowToDelete.remove();
                } else if (result == 'fail') {
                    alert("An error occured!");
                }
            },
            fail: function(result) {
                alert("An error occured!");
            }
        });
    });

    // private message
    $('#sendMeAPrivateMessage').on('click', function() {
        $('#newConversationModal').modal('show');
        $('#toUser').val($(this).val());
        $('#toUser').attr('readonly', 'true');
    });
});

function readImage(file) {
    if (file.type.match(/image.*/)) {
        var reader = new FileReader();
        var image = document.createElement("img");

        reader.readAsDataURL(file);
        reader.onload = function(_file) {
            image.src = _file.target.result;
            image.onload = function() {
                var dimensions = newDimensions(image.width, image.height, 150, 150);
                image.width = dimensions.width;
                image.height = dimensions.height;
                image.className = "currentOrNewUserAvatarImage";
                $('#newAvatar').empty();
                $('#newAvatar').html("New avatar:<br/>");
                $('#newAvatar').append(image);
                $('#newAvatar').show();
                $('#errorMessages').hide();
                $('#submitChangeAvatarForm').removeClass('disabled');
            }
        }
    }
    else {
        $('#errorMessages').text('The uploaded file must be a photo!');
        $('#newAvatar').hide();
        $('#errorMessages').show();
        $('#submitChangeAvatarForm').addClass('disabled');
    }
}

function activateChangePasswordButtonIfPossible() {
    var oldPasswordInput = $('#userOldPassword').parent();
    var userNewPasswordVerification = $('#userNewPasswordVerification').parent();

    if (oldPasswordInput.hasClass('has-success') && userNewPasswordVerification.hasClass('has-success')) {
        $('#submitChangePassword').removeClass('disabled');
    } else {
        $('#submitChangePassword').addClass('disabled');
    }
}