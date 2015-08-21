$(document).ready(function($) {
    //duration of the top scrolling animation (in ms)
    var scrollTopDuration = 500;
    //grab the "back to top" link
    var backToTopAnchor = $('.cd-top');
    var backToTopButton = $('.backToTop');

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
});