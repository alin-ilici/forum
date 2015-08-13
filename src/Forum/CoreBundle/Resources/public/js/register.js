$(document).ready(function($) {
    $('#user_username').on('blur', function() {
        var parent = $('#user_username').parent();

        if ($(this).val() != '') {
            $.ajax({
                type: 'post',
                url: Routing.generate('forum_core_register_check_for'),
                data: {
                    what: 'username',
                    whatValue: $(this).val()
                },
                dataType: 'json',
                success: function(result) {
                    var parent = $('#user_username').parent();

                    if (result == 'success') {
                        parent.removeClass();
                        parent.addClass("form-group has-success has-feedback");
                        parent.find('span').remove();
                        parent.append(
                            '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>' +
                            '<span id="inputUsernameVerStatus" class="sr-only">(success)</span>'
                        );
                        $('#inputUsernameVerMessage').remove();
                        activateRegisterButtonIfPossible();
                    } else {
                        parent.removeClass();
                        parent.addClass("form-group has-error has-feedback");
                        parent.find('span').remove();
                        parent.append(
                            '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                            '<span id="inputUsernameVerStatus" class="sr-only">(error)</span>'
                        );
                        $('#inputUsernameVerMessage').remove();
                        parent.after('<div id="inputUsernameVerMessage" class="alert alert-danger" role="alert">This username is already taken!</div>');
                        $('#user_save').addClass('disabled');
                    }
                }
            });
        } else {
            parent.removeClass();
            parent.addClass("form-group has-error has-feedback");
            parent.find('span').remove();
            parent.append(
                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                '<span id="inputUsernameVerStatus" class="sr-only">(error)</span>'
            );
            $('#inputUsernameVerMessage').remove();
            parent.after('<div id="inputUsernameVerMessage" class="alert alert-danger" role="alert">The `username` field can not be empty!</div>');
            $('#user_save').addClass('disabled');
        }
    });

    $('#userPasswordVerification, #user_password').on('blur', function() {
        var realPassword = $('#user_password').val();
        var parent = $('#userPasswordVerification').parent();

        if ($('#userPasswordVerification').val() != '' &&
            $('#user_password').val() != '') {
            if (realPassword != $('#userPasswordVerification').val()) {
                parent.removeClass();
                parent.addClass("form-group has-error has-feedback");
                parent.find('span').remove();
                parent.append(
                    '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                    '<span id="inputPassVerStatus" class="sr-only">(error)</span>'
                );
                $('#inputRetypePasswordVerMessage').remove();
                parent.after('<div id="inputRetypePasswordVerMessage" class="alert alert-danger" role="alert">The passwords do not match!</div>');
                $('#user_save').addClass('disabled');
            } else {
                parent.removeClass();
                parent.addClass("form-group has-success has-feedback");
                parent.find('span').remove();
                parent.append(
                    '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>' +
                    '<span id="inputPassVerStatus" class="sr-only">(success)</span>'
                );
                $('#inputRetypePasswordVerMessage').remove();
                activateRegisterButtonIfPossible();
            }
        } else if (($('#userPasswordVerification').val() == '' && $('#user_password').val() != '') ||
            ($('#userPasswordVerification').val() == '' && $('#user_password').val() == '')) {
            parent.removeClass();
            parent.addClass('form-group');
            parent.find('span').remove();
            $('#inputRetypePasswordVerMessage').remove();
            $('#user_save').addClass('disabled');
        } else if ($('#userPasswordVerification').val() != '' &&
            $('#user_password').val() == '') {
            parent.removeClass();
            parent.addClass("form-group has-error has-feedback");
            parent.find('span').remove();
            parent.append(
                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                '<span id="inputPassVerStatus" class="sr-only">(error)</span>'
            );
            $('#inputRetypePasswordVerMessage').remove();
            parent.after('<div id="inputRetypePasswordVerMessage" class="alert alert-danger" role="alert">The passwords do not match!</div>');
            $('#user_save').addClass('disabled');
        }
    });

    $('#user_email').on('blur', function() {
        var emailRegex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        var parent = $('#user_email').parent();

        if ($(this).val() != '') {
            if (emailRegex.test($('#user_email').val())) {
                $.ajax({
                    type: 'post',
                    url: Routing.generate('forum_core_register_check_for'),
                    data: {
                        what: 'email',
                        whatValue: $(this).val()
                    },
                    dataType: 'json',
                    success: function(result) {
                        var parent = $('#user_email').parent();

                        if (result == 'success') {
                            parent.removeClass();
                            parent.addClass("form-group has-success has-feedback");
                            parent.find('span').remove();
                            parent.append(
                                '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>' +
                                '<span id="inputEmailVerStatus" class="sr-only">(success)</span>'
                            );
                            $('#inputEmailVerMessage').remove();
                            activateRegisterButtonIfPossible();
                        } else {
                            parent.removeClass();
                            parent.addClass("form-group has-error has-feedback");
                            parent.find('span').remove();
                            parent.append(
                                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                                '<span id="inputEmailVerStatus" class="sr-only">(error)</span>'
                            );
                            $('#inputEmailVerMessage').remove();
                            parent.after('<div id="inputEmailVerMessage" class="alert alert-danger" role="alert">There is already an account registered on this email!</div>');
                            $('#user_save').addClass('disabled');
                        }
                    }
                });
            } else {
                $('#inputEmailVerMessage').remove();
                parent.after('<div id="inputEmailVerMessage" class="alert alert-danger" role="alert">This is not a valid email address!</div>');
                $('#user_save').addClass('disabled');
            }
        } else {
            parent.removeClass();
            parent.addClass("form-group has-error has-feedback");
            parent.find('span').remove();
            parent.append(
                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                    '<span id="inputEmailVerStatus" class="sr-only">(error)</span>'
            );
            $('#inputEmailVerMessage').remove();
            parent.after('<div id="inputEmailVerMessage" class="alert alert-danger" role="alert">The `email` field can not be empty!</div>');
            $('#user_save').addClass('disabled');
        }
    });

    $("#user_avatar").on('change', function(event) {
        if (this.disabled)
            return alert('File upload not supported!');
        var F = this.files;
        if (F && F[0]) {
            for (var i = 0; i < F.length; i++) {
                readImage(F[i]);
            }
        } else {
            $('#avatarPreview').hide();
            $('#errorMessages').hide();
        }
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
                var dimensions = newDimensions(image.width, image.height, 200, 200);
                image.width = dimensions.width;
                image.height = dimensions.height;
                $('#avatarPreview').empty();
                $('#errorMessages').hide();
                $('#avatarPreview').append(image);
                $('#avatarPreview').show();
            }
        }
    }
    else {
        $('#errorMessages').text('The uploaded file must be a photo!');
        $('#avatarPreview').hide();
        $('#errorMessages').show();
    }
}

function activateRegisterButtonIfPossible() {
    var usernameInput = $('#user_username').parent();
    var passwordVerificationInput = $('#userPasswordVerification').parent();
    var emailInput = $('#user_email').parent();

    if (usernameInput.hasClass('has-success') &&
        passwordVerificationInput.hasClass('has-success') &&
        emailInput.hasClass('has-success')) {
        $('#user_save').removeClass('disabled');
    } else {
        $('#user_save').addClass('disabled');
    }
}