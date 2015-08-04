$(document).ready(function($) {
    //duration of the top scrolling animation (in ms)
    var scrollTopDuration = 500;
    //grab the "back to top" link
    var backToTopAnchor = $('.cd-top');
    var backToTopButton = $('.backToTop');
    var firstFormActionUrl = $('[name="message"]').attr('action');

    function preventDefault(e) {
        e = e || window.event;
        if (e.preventDefault)
            e.preventDefault();
        e.returnValue = false;
    }

    function keydown(e) {
        for (var i = keys.length; i--;) {
            if (e.keyCode === keys[i]) {
                preventDefault(e);
                return;
            }
        }
    }

    function wheel(e) {
        preventDefault(e);
    }

    function disable_scroll() {
        if (window.addEventListener) {
            window.addEventListener('DOMMouseScroll', wheel, false);
        }
        window.onmousewheel = document.onmousewheel = wheel;
        document.onkeydown = keydown;
    }

    function enable_scroll() {
        if (window.removeEventListener) {
            window.removeEventListener('DOMMouseScroll', wheel, false);
        }
        window.onmousewheel = document.onmousewheel = document.onkeydown = null;
    }

    //smooth scroll to top
    backToTopAnchor.on('click', function(e) {
        e.preventDefault();

        disable_scroll();

        $('body, html').stop().animate(
            {
                scrollTop: 0
            },
            scrollTopDuration,
            function() {
                enable_scroll();
            }
        );
    });

    backToTopButton.on('click', function(e) {
        backToTopAnchor[0].click();
    });

    // page buttons functionality
    $('.deleteMessage').on('click', function () {
        $.ajax({
            type: 'post',
            url: '/topic/delete/' + $(this).val(),
            dataType: 'json',
            success: function(result) {
                window.location.replace(window.location.href);
            },
            fail: function() {
                alert("An error occured!");
            }
        });
    });

    $('.editMessage').on('click', function () {
        var prevTr = $(this).parent().parent().parent().prev();
        var message = prevTr.find('.messageContent').find('script');
        var messageText = message.text().slice(0, message.text().indexOf('.text();')) + '.text();';
        eval(messageText);

        //variable text used below comes from eval(messageText)
        $('#message_name').val('');

        $('#message_name').val(text);
        $('[name="message"]').attr('action', firstFormActionUrl);
        var form = $('[name="message"]');
        var formActionUrl = form.attr('action');
        var newFormActionUrl = formActionUrl + '/' + $(this).val();
        $('[name="message"]').attr('action', newFormActionUrl);

        $('html, body').animate(
            {
                scrollTop: $('[name="message"]').offset().top
            },
            'slow'
        );
        $('#cancelEditMessage').show();
    });

    $('#cancelEditMessage').on('click', function() {
        $('[name="message"]').attr('action', firstFormActionUrl);
        $('#message_name').val('');
        $(this).hide();
    });
});