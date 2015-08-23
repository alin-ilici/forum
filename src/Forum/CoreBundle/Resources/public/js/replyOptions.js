$(document).ready(function() {
    var elementToWriteIn = 'message_name', prefix = '', insertFileElementToBeTriggered = 'message_file';

    // PM means private message, T means topic
    // used from conversation.html.twig (the (reply) message from the bottom of the page)
    if ($('#whatPageIsLoaded').text() == 'conversation') {
        elementToWriteIn = 'privateMessageText';
        prefix = 'PM';
        insertFileElementToBeTriggered = 'uploadedFilePM';
    }

    // private message modal logic
    $(document).on('shown.bs.modal', '#newConversationModal', function() {
        elementToWriteIn = 'privateMessageText';
        prefix = 'PM';
        insertFileElementToBeTriggered = 'uploadedFilePM';
    });

    $(document).on('hidden.bs.modal', '#newConversationModal', function() {
        elementToWriteIn = 'message_name';
        prefix = '';
        insertFileElementToBeTriggered = 'message_file';

        var parent = $('#toUser').parent();
        parent.removeClass();
        parent.addClass("form-group");
        parent.find('span').remove();
        $('#inputUsernameVerMessage').remove();
        $('#sendConversationButton').addClass('disabled');

        $('#toUser').val('');
        $('#conversationName').val('');
        $('#privateMessageText').val('');
        $('#emoticonsZonePM').hide();
    });

    // topic modal logic
    $(document).on('shown.bs.modal', '#newTopicModal', function() {
        elementToWriteIn = 'messageText';
        prefix = 'T';
        insertFileElementToBeTriggered = 'uploadedFileT';
    });

    $(document).on('hidden.bs.modal', '#newTopicModal', function() {
        $('#topicName').val('');
        $('#messageText').val('');
        $('#emoticonsZoneT').hide();
    });

    $(document).on('hidden.bs.modal', '#addHyperlinkModal, #addHyperlinkModalPM, #addHyperlinkModalT', function(event) {
        $('#hyperlink' + prefix).val('');
        $('#confirmAddHyperlinkButton' + prefix).addClass('disabled');
    });

    $(document).on('click', '#boldText, #boldTextPM, #boldTextT', function() {
        updateMessage(elementToWriteIn, '<b>', '</b>');
    });

    $(document).on('click', '#italicText, #italicTextPM, #italicTextT', function() {
        updateMessage(elementToWriteIn, '<i>', '</i>');
    });

    $(document).on('click', '#underlinedText, #underlinedTextPM, #underlinedTextT', function() {
        updateMessage(elementToWriteIn, '<u>', '</u>');
    });

    $(document).on('click', '#hyperlinkText, #hyperlinkTextPM, #hyperlinkTextT', function() {
        var selection = getSelection(document.getElementById(elementToWriteIn));

        if (selection.startPos != selection.endPos) {
            $('#addHyperlinkModal' + prefix).modal('show');
        }
    });

    $(document).on('keyup', '#hyperlink, #hyperlinkPM, #hyperlinkT', function() {
        if ($(this).val() == '') {
            $('#confirmAddHyperlinkButton' + prefix).addClass('disabled');
        } else {
            $('#confirmAddHyperlinkButton' + prefix).removeClass('disabled');
        }
    });

    $(document).on('click', '#confirmAddHyperlinkButton, #confirmAddHyperlinkButtonPM, #confirmAddHyperlinkButtonT', function(e) {
        var hyperlink = $('#hyperlink' + prefix).val();

        updateMessage(elementToWriteIn, '<a href="' + hyperlink + '">', '</a>');
    });

    $(document).on('click', '#insertFile, #insertFilePM, #insertFileT', function() {
        $('#' + insertFileElementToBeTriggered).click();
    });

    $(document).on('change', '#message_file, #uploadedFilePM, #uploadedFileT', function() {
        var fileName = $(this).val().split('\\');
        if (fileName.length != 1) {
            $('#uploadedFileName' + prefix).text(fileName[fileName.length - 1]);
            $('#uploadedFileNameDiv' + prefix).show();
        } else {
            $('#uploadedFileNameDiv' + prefix).hide();
        }
    });

    $(document).on('click', '#deleteUploadedFile, #deleteUploadedFilePM, #deleteUploadedFileT', function() {
        $('#' + insertFileElementToBeTriggered).val('');
        $('#uploadedFileNameDiv' + prefix).hide();
    });

    // this is used only for the new private message case from every's user private messages homepage
    $(document).on('blur', '#toUser', function() {
        var parent = $('#toUser').parent();

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
                    var parent = $('#toUser').parent();

                    if (result == 'fail') {
                        parent.removeClass();
                        parent.addClass("form-group has-success has-feedback");
                        parent.find('span').remove();
                        parent.append(
                            '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>' +
                            '<span id="inputUsernameVerStatus" class="sr-only">(success)</span>'
                        );
                        $('#inputUsernameVerMessage').remove();
                        $('#sendConversationButton').removeClass('disabled');
                    } else {
                        parent.removeClass();
                        parent.addClass("form-group has-error has-feedback");
                        parent.find('span').remove();
                        parent.append(
                            '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>' +
                            '<span id="inputUsernameVerStatus" class="sr-only">(error)</span>'
                        );
                        $('#inputUsernameVerMessage').remove();
                        parent.after('<div id="inputUsernameVerMessage" class="alert alert-danger" role="alert">This username does not exist!</div>');
                        $('#sendConversationButton').addClass('disabled');
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
            parent.after('<div id="inputUsernameVerMessage" class="alert alert-danger" role="alert">The `To` field can not be empty!</div>');
            $('#sendConversationButton').addClass('disabled');
        }
    });
});

function updateMessage(fieldToUpdateId, selectorStartTag, selectorEndTag)
{
    var selection = getSelection(document.getElementById(fieldToUpdateId));

    var text = $('#' + fieldToUpdateId).val();

    var beforeSelection = text.substring(selection.startPos - selectorStartTag.length, selection.startPos);
    var afterSelection = text.substring(selection.endPos, selection.endPos + selectorEndTag.length);

    if (beforeSelection != selectorStartTag && afterSelection != selectorEndTag) {
        $('#' + fieldToUpdateId).val([text.slice(0, selection.endPos), selectorEndTag, text.slice(selection.endPos)].join(''));
        text = $('#' + fieldToUpdateId).val();
        $('#' + fieldToUpdateId).val([text.slice(0, selection.startPos), selectorStartTag, text.slice(selection.startPos)].join(''));
    } else if (beforeSelection == selectorStartTag && afterSelection == selectorEndTag) {
        $('#' + fieldToUpdateId).val(text.replace(text.substring(selection.endPos, selection.endPos + selectorEndTag.length), ''));
        text = $('#' + fieldToUpdateId).val();
        $('#' + fieldToUpdateId).val(text.replace(text.substring(selection.startPos - selectorStartTag.length, selection.startPos), ''));
    }
}

function getSelection(textComponent)
{
    var selectedText;
    var startPos = -1;
    var endPos = -1;

    // IE version
    if (document.selection != undefined)
    {
        textComponent.focus();
        var sel = document.selection.createRange();
        selectedText = sel.text;
    }

    // Mozilla version
    else if (textComponent.selectionStart != undefined)
    {
        startPos = textComponent.selectionStart;
        endPos = textComponent.selectionEnd;
        selectedText = textComponent.value.substring(startPos, endPos)
    }

    return {
        selectedText: selectedText,
        startPos: startPos,
        endPos: endPos
    }
}

function parseMessage(message, level) {
    var matchQuoteStart = message.match(/\[quote.*? name=\'.*?\' timestamp=\'.*?\'\]/);

    if (matchQuoteStart != null) {
        matchQuoteStart = matchQuoteStart[0];

        var quoteNumber = (matchQuoteStart.match(/\[quote.*? /))[0];
        quoteNumber = quoteNumber.substring(6, quoteNumber.length - 1);

        var name = (matchQuoteStart.match(/name=\'.*?\'/))[0];
        name = name.substring(6, name.length - 1);

        var date = (matchQuoteStart.match(/timestamp=\'.*?\'/))[0];
        date = date.substring(11, date.length - 1);

        var quoteStartPosition = message.indexOf(matchQuoteStart);
        var quoteEndPosition = message.lastIndexOf('[/quote' + quoteNumber + ']');

        var pureMessage = message.substring(quoteStartPosition + matchQuoteStart.length, quoteEndPosition);

        var newMessage = '<div class="myQuote"><div class="myQuoteHeader">' + name + ', on ' + date + ' said:</div><div class="myQuoteMessage">' + pureMessage + '</div></div>';

        message = message.replace(matchQuoteStart + pureMessage + '[/quote' + quoteNumber + ']', newMessage);

        message = parseMessage(message, level + 1);
    }

    return message;
}