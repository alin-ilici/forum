$(document).ready(function()
{
    $('#logInButton').click(function() {
        $.ajax({
            type: 'post',
            url: Routing.generate('forum_core_security_login'),
            data: '',
            success: function(result) {
                if ($("#logInModal").children.length > 0) {
                    $('#logInModal').empty();
                }
                $("#logInModal").append(result);
                $('#logInForm').modal('show');
            }
        });
    });

    $('#logOutButton').click(function() {
        window.location = '/logout';
    });

    $('#registerButton').click(function() {
        window.location = '/register';
    });

    $(document).on('click', '#submitLoginForm', function() {
        $.ajax({
            type: 'post',
            url: Routing.generate('forum_core_security_login_check'),
            data: {
                _username: $('#username').val(),
                _password: $('#password').val(),
                _remember_me: $('#remember_me').is(':checked')
            },
            success: function(result) {
                if (result.success) {
                    $('#formToSubmit').submit();
                    location.reload();
                } else {
                    if ($('#logInForm .modal-body').find('#logInErrorDiv').length == 0) {
                        $('#logInForm .modal-body').prepend(
                            "<div id='logInErrorDiv' class='alert alert-danger alert-dismissible' role='alert'>" +
                                "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                                "<div id='logInErrorMessage'>" + result.message + "</div>" +
                            "</div>");
                    } else {
                        $('#logInErrorMessage').text(result.message);
                    }
                }
            }
        });
    });

    $(document).on('shown.bs.modal', '#logInForm', function() {
        $('#username').focus();
    });

    $(document).on('keypress', function(e) {
        //if (($('#logInForm').data('bs.modal') || {}).isShown) {
        if ($('#logInForm').hasClass('in')) {
            if (e.keyCode == 13) {
                $('#submitLoginForm').click();
            }
        }
    });

    // w and h are the actual photo's width and height
    var newDimensions = function(w, h, maxWidth, maxHeight) {
        var MAX_WIDTH = maxWidth;
        var MAX_HEIGHT = maxHeight;

        if (w > h) {
            if (w > MAX_WIDTH) {
                h *= MAX_WIDTH / w;
                w = MAX_WIDTH;
            }
        } else {
            if (w < h) {
                if (h > MAX_HEIGHT) {
                    w *= MAX_HEIGHT / h;
                    h = MAX_HEIGHT;
                }
            } else {
                if (w == h) {
                    w = MAX_WIDTH;
                    h = MAX_WIDTH;
                }
            }
        }
        return {
            width: w,
            height: h
        };
    }

});