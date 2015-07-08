$(document).ready(function()
{
    $('#logInButton').click(function() {
        $.ajax({
            type: 'post',
            url: '/login',
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

    $(document).on('click', '#submitLoginForm', function() {
        $.ajax({
            type: 'post',
            url: '/login_check',
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

});