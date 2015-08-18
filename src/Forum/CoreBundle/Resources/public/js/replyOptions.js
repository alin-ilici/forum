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