function parseMessage(message, level) {
    var matchQuoteStart = message.match(/\[quote name=\'.*?\' timestamp=\'.*?\'\]/);

    if (matchQuoteStart == null) {
        return message;
    } else if (matchQuoteStart != null) {
        matchQuoteStart = matchQuoteStart[0];

        var name = (matchQuoteStart.match(/name=\'.*?\'/))[0];
        name = name.substring(6, name.length - 1);

        var date = (matchQuoteStart.match(/timestamp=\'.*?\'/))[0];
        date = date.substring(11, date.length - 1);

        var quoteStartPosition = message.indexOf(matchQuoteStart);
        var quoteEndPosition = message.lastIndexOf('[/quote]');

        var pureMessage = message.substring(quoteStartPosition + matchQuoteStart.length, quoteEndPosition);

        var newMessage = '<div class="quote"><div class="quoteHeader">' + name + ', on ' + date + ' said:</div><div class="quoteMessage">' + pureMessage + '</div></div>';

        if (level == 0) {
            message = newMessage;
        } else {
            ;
//            message = message.replace(pureMessage, newMessage);
        }

        console.log(message);

        parseMessage(message, level + 1);
    }
}