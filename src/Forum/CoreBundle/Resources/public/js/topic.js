$(document).ready(function($) {
    var firstFormActionUrl = $('[name="message"]').attr('action');
    var messageId = null;

//    function placeCaretAtEnd(el) {
//        el.focus();
//        if (typeof window.getSelection != "undefined"
//            && typeof document.createRange != "undefined") {
//            var range = document.createRange();
//            range.selectNodeContents(el);
//            range.collapse(false);
//            var sel = window.getSelection();
//            sel.removeAllRanges();
//            sel.addRange(range);
//        } else if (typeof document.body.createTextRange != "undefined") {
//            var textRange = document.body.createTextRange();
//            textRange.moveToElementText(el);
//            textRange.collapse(false);
//            textRange.select();
//        }
//    }
//
//    $('#messageNameEditableDiv').on('keyup', function(e) {
////        if (e.keyCode == 32) {
////        }
////        setTimeout(function() {
//            var emotifiedMessage = emotify($('#messageNameEditableDiv').html());
//            $(this).html(emotifiedMessage);
//            placeCaretAtEnd(document.getElementById("messageNameEditableDiv"));
////        }, 1000);
//        console.log($(this).html());
//    });

    // page buttons functionality
    // delete
    $('.deleteMessage').on('click', function () {
        $.ajax({
            type: 'post',
            url: Routing.generate('forum_core_topic_delete_message', { messageId: $(this).val() }),
            dataType: 'json',
            success: function(result) {
                window.location.replace(window.location.href);
            },
            fail: function() {
                alert("An error occured when trying to delete the message!");
            }
        });
    });

    // edit
    $('.editMessage').on('click', function () {
        var prevPrevPrevTr = $(this).parent().parent().parent().prev().prev().prev();
        var message = prevPrevPrevTr.find('.messageFromDb').text();

        $('#message_name').val('');

        $('#message_name').val(message);

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

    // move
    $('.moveMessage').on('click', function() {
        messageId = $(this).val();

        $('#forumSelect select').val('noValue');
        $('#categorySelect').empty();
        $('#subcategorySelect').empty();
        $('#topicSelect').empty();

        $('#categorySelect').hide();
        $('#subcategorySelect').hide();
        $('#topicSelect').hide();
        $('#finishMovingMessage').addClass('disabled');

        $('#moveMessageModalWindow').modal('show');
    });

    $('#forumSelect select').on('change', function() {
        $.ajax({
            type: 'post',
            url: Routing.generate('forum_core_topic_move_message', { messageId: messageId }),
            data: { forum: $('#forumSelect select').find(":selected").val() },
            dataType: 'json',
            success: function(result) {
                if (result != 'showOnlyForum') {
                    $('#categorySelect').empty();
                    $('#categorySelect').hide();
                    $('#subcategorySelect').hide();
                    $('#topicSelect').hide();
                    $('#finishMovingMessage').addClass('disabled');

                    var html = 'Category: <select>';
                    for (var key in result) {
                        if (result.hasOwnProperty(key)) {
                            html = html + '<option value="' + result[key] + '">' + key + '</option>';
                        }
                    }
                    html = html + '</select>';

                    $('#categorySelect').append(html);
                    $('#categorySelect').show();

                    $('#categorySelect select').on('change', function() {
                        $.ajax({
                            type: 'post',
                            url: Routing.generate('forum_core_topic_move_message', { messageId: messageId }),
                            data: { category: $('#categorySelect select').find(":selected").val() },
                            dataType: 'json',
                            success: function(result) {
                                if (result != 'showOnlyCategory') {
                                    $('#subcategorySelect').empty();
                                    $('#subcategorySelect').hide();
                                    $('#topicSelect').hide();
                                    $('#finishMovingMessage').addClass('disabled');

                                    var html = 'Subcategory: <select>';
                                    for (var key in result) {
                                        if (result.hasOwnProperty(key)) {
                                            html = html + '<option value="' + result[key] + '">' + key + '</option>';
                                        }
                                    }
                                    html = html + '</select>';

                                    $('#subcategorySelect').append(html);
                                    $('#subcategorySelect').show();

                                    $('#subcategorySelect select').on('change', function() {
                                        $.ajax({
                                            type: 'post',
                                            url: Routing.generate('forum_core_topic_move_message', { messageId: messageId }),
                                            data: { subcategory: $('#subcategorySelect select').find(":selected").val() },
                                            dataType: 'json',
                                            success: function(result) {
                                                if (result != 'showOnlySubcategory') {
                                                    $('#topicSelect').empty();
                                                    $('#topicSelect').hide();
                                                    $('#finishMovingMessage').addClass('disabled');

                                                    var html = 'Topic: <select>';
                                                    for (var key in result) {
                                                        if (result.hasOwnProperty(key)) {
                                                            html = html + '<option value="' + result[key] + '">' + key + '</option>';
                                                        }
                                                    }
                                                    html = html + '</select>';
                                                    if (Object.keys(result).length == 1) {
                                                        html = 'Nu exista nici un topic in subcategoria selectata!';
                                                        $('#finishMovingMessage').addClass('disabled');
                                                    }

                                                    $('#topicSelect').append(html);
                                                    $('#topicSelect').show();

                                                    $('#topicSelect select').on('change', function() {
                                                        if ($('#topicSelect select').find(":selected").val() != 'noValue') {
                                                            $('#finishMovingMessage').removeClass('disabled');
                                                        } else {
                                                            $('#finishMovingMessage').addClass('disabled');
                                                        }
                                                    });
                                                } else {
                                                    $('#topicSelect').hide();
                                                    $('#finishMovingMessage').addClass('disabled');
                                                }
                                            },
                                            fail: function() {
                                                alert("An error occured when trying to move the message!");
                                            }
                                        });
                                    });
                                } else {
                                    $('#subcategorySelect').hide();
                                    $('#topicSelect').hide();
                                    $('#finishMovingMessage').addClass('disabled');
                                }
                            },
                            fail: function() {
                                alert("An error occured when trying to move the message!");
                            }
                        });
                    });
                } else {
                    $('#categorySelect').hide();
                    $('#subcategorySelect').hide();
                    $('#topicSelect').hide();
                    $('#finishMovingMessage').addClass('disabled');
                }
            },
            fail: function() {
                alert("An error occured when trying to move the message!");
            }
        });
    });

    $('#finishMovingMessage').on('click', function() {
        $.ajax({
            type: 'post',
            url: Routing.generate('forum_core_topic_move_message', { messageId: messageId }),
            data: { topic: $('#topicSelect select').find(":selected").val() },
            dataType: 'json',
            success: function(result) {
                window.location.href = result;
            },
            fail: function() {
                alert("An error occured when trying to move the message!");
            }
        });
    });

    // quote
    $('.quoteMessage').on('click', function() {
        var prevPrevPrevTr = $(this).parent().parent().parent().prev().prev().prev();
        var message = prevPrevPrevTr.find('.messageFromDb').text();
        var user = prevPrevPrevTr.find('.messageUsernameFromDb').text();
        var datePosted = prevPrevPrevTr.find('.messageDateCreatedFromDb').text();

        var random = getRandomInt(10000, 99999);

        $('#message_name').val($('#message_name').val() + '[quote' + random + ' name=\'' + user + '\' timestamp=\'' + datePosted + '\']' + message + '[/quote' + random + ']');
    });

    // private message
    $('.sendMeAMessage').on('click', function() {
        $('#newConversationModal').modal('show');
        $('#toUser').val($(this).attr('data-value'));
        $('#toUser').attr('readonly', 'true');
    });
});

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}