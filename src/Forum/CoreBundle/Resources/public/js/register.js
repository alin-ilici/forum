$(document).ready(function($) {
    $('#userPasswordVerification').on('blur', function() {
        var realPassword = $('#user_password').val();
        var parent = $(this).parent();

        if (realPassword != $(this).val()) {
            parent.removeClass();
            parent.addClass("form-group has-error has-feedback");
            parent.find('span').remove();
            parent.append(
                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                '<span id="inputPassVerStatus" class="sr-only">(error)</span>'
            );
        } else {
            parent.removeClass();
            parent.addClass("form-group has-success has-feedback");
            parent.find('span').remove();
            parent.append(
                '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>' +
                '<span id="inputPassVerStatus" class="sr-only">(success)</span>'
            );
        }
    });

    $("#user_avatar").change(function(event) {
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