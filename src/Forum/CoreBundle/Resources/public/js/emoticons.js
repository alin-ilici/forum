var smilies = {
/*smiley     image_url          title_text              alt_smilies            */
    ":)":    [ "1.gif",           "happy",                ":-)"                 ],
    ":(":    [ "2.gif",           "sad",                  ":-("                 ],
    ";)":    [ "3.gif",           "winking",              ";-)"                 ],
    ":D":    [ "4.gif",           "big grin",             ":-D"                 ],
    ";;)":   [ "5.gif",           "batting eyelashes"                           ],
    ">:D<":  [ "6.gif",           "big hug"                                     ],
    ":-/":   [ "7.gif",           "confused",             ":/"                  ],
    ":x":    [ "8.gif",           "love struck",          ":X"                  ],
    ":\">":  [ "9.gif",           "blushing"                                    ],
    ":P":    [ "10.gif",          "tongue",               ":p", ":-p", ":-P"    ],
    ":-*":   [ "11.gif",          "kiss",                 ":*"                  ],
    "=((":   [ "12.gif",          "broken heart"                                ],
    ":-O":   [ "13.gif",          "surprise",             ":O"                  ],
    "X(":    [ "14.gif",          "angry"                                       ],
    ":>":    [ "15.gif",          "smug"                                        ],
    "B-)":   [ "16.gif",          "cool"                                        ],
    ":-S":   [ "17.gif",          "worried"                                     ],
    "#:-S":  [ "18.gif",          "whew!",                "#:-s"                ],
    ">:)":   [ "19.gif",          "devil",                ">:-)"                ],
    ":((":   [ "20.gif",          "crying",               ":-((", ":'(", ":'-(" ],
    ":))":   [ "21.gif",          "laughing",             ":-))"                ],
    ":|":    [ "22.gif",          "straight face",        ":-|"                 ],
    "/:)":   [ "23.gif",          "raised eyebrow",       "/:-)"                ],
    "=))":   [ "24.gif",          "rolling on the floor"                        ],
    "O:-)":  [ "25.gif",          "angel",                "O:)"                 ],
    ":-B":   [ "26.gif",          "nerd"                                        ],
    "=;":    [ "27.gif",          "talk to the hand"                            ],
    "I-)":   [ "28.gif",          "sleepy"                                      ],
    "8-|":   [ "29.gif",          "rolling eyes"                                ],
    "L-)":   [ "30.gif",          "loser"                                       ],
    ":-&":   [ "31.gif",          "sick"                                        ],
    ":-$":   [ "32.gif",          "don't tell anyone"                           ],
    "[-(":   [ "33.gif",          "not talking"                                 ],
    ":O)":   [ "34.gif",          "clown"                                       ],
    "8-}":   [ "35.gif",          "silly"                                       ],
    "<:-P":  [ "36.gif",          "party",                "<:-p"                ],
    "(:|":   [ "37.gif",          "yawn"                                        ],
    "=P~":   [ "38.gif",          "drooling"                                    ],
    ":-?":   [ "39.gif",          "thinking"                                    ],
    "#-o":   [ "40.gif",          "d'oh",                 "#-O"                 ],
    "=D>":   [ "41.gif",          "applause"                                    ],
    ":-SS":  [ "42.gif",          "nailbiting",           ":-ss"                ],
    "@-)":   [ "43.gif",          "hypnotized"                                  ],
    ":^o":   [ "44.gif",          "liar"                                        ],
    ":-w":   [ "45.gif",          "waiting",              ":-W"                 ],
    ":-<":   [ "46.gif",          "sigh"                                        ],
    ">:P":   [ "47.gif",          "phbbbbt",              ">:p"                 ],
    "<):)":  [ "48.gif",          "cowboy"                                      ],
    ":@)":   [ "49.gif",          "pig"                                         ],
    "3:-O":  [ "50.gif",          "cow",                  "3:-o"                ],
    ":(|)":  [ "51.gif",          "monkey"                                      ],
    "~:>":   [ "52.gif",          "chicken"                                     ],
    "@};-":  [ "53.gif",          "rose"                                        ],
    "%%-":   [ "54.gif",          "good luck"                                   ],
    "**==":  [ "55.gif",          "flag"                                        ],
    "(~~)":  [ "56.gif",          "pumpkin"                                     ],
    "~O)":   [ "57.gif",          "coffee"                                      ],
    "*-:)":  [ "58.gif",          "idea"                                        ],
    "8-X":   [ "59.gif",          "skull"                                       ],
    "=:)":   [ "60.gif",          "bug"                                         ],
    ">-)":   [ "61.gif",          "alien"                                       ],
    ":-L":   [ "62.gif",          "frustrated",           ":L"                  ],
    "[-O<":  [ "63.gif",          "praying"                                     ],
    "$-)":   [ "64.gif",          "money eyes"                                  ],
    ":-\"":  [ "65.gif",          "whistling"                                   ],
    "b-(":   [ "66.gif",          "feeling beat up"                             ],
    ":)>-":  [ "67.gif",          "peace sign"                                  ],
    "[-X":   [ "68.gif",          "shame on you"                                ],
    "\\:D/": [ "69.gif",          "dancing"                                     ],
    ">:/":   [ "70.gif",          "bring it on"                                 ],
    ";))":   [ "71.gif",          "hee hee"                                     ],
    "o->":   [ "72.gif",          "hiro"                                        ],
    "o=>":   [ "73.gif",          "billy"                                       ],
    "o-+":   [ "74.gif",          "april"                                       ],
    "(%)":   [ "75.gif",          "yin yang"                                    ],
    ":-@":   [ "76.gif",          "chatterbox"                                  ],
    "^:)^":  [ "77.gif",          "not worthy"                                  ],
    ":-j":   [ "78.gif",          "oh go on"                                    ],
    "(*)":   [ "79.gif",          "star"                                        ],
    ":)]":   [ "100.gif",         "on the phone"                                ],
    ":-c":   [ "101.gif",         "call me"                                     ],
    "~X(":   [ "102.gif",         "at wits' end"                                ],
    ":-h":   [ "103.gif",         "wave"                                        ],
    ":-t":   [ "104.gif",         "time out"                                    ],
    "8->":   [ "105.gif",         "daydreaming"                                 ],
    ":-??":  [ "106.gif",         "I don't know"                                ],
    "%-(":   [ "107.gif",         "not listening"                               ],
    ":o3":   [ "108.gif",         "puppy dog eyes"                              ],
    "X_X":   [ "109.gif",         "I don't want to see",  "x_x"                 ],
    ":!!":   [ "110.gif",         "hurry up!"                                   ],
    "\\m/":  [ "111.gif",         "rock on!"                                    ],
    ":-q":   [ "112.gif",         "thumbs down"                                 ],
    ":-bd":  [ "113.gif",         "thumbs up"                                   ],
    "^#(^":  [ "114.gif",         "it wasn't me"                                ],
    ":bz":   [ "115.gif",         "bee"                                         ],
    ":ar!":  [ "pirate.gif",      "pirate"                                      ],
    "[..]":  [ "transformer.gif", "transformer"                                 ]
};

function emotify_message(message) {
    // Add the smilies, setting the appropirate base_url.
    emotify.emoticons( '../../../../../bundles/core/emoticons/', smilies );

    return emotify(message);
}

$(document).ready(function($) {
    var html = '';

    // Generate "emoticons key" table for this example.
    $.each(emotify.emoticons(), function(k, v) {
        html = html + emotify(k);
    });

    $('#emoticonsZone').html(html);

    $('.smiley').on('click', function() {
        var imageTitle,
            textareaContent;
        imageTitle = $(this).attr('title');
        imageTitle = imageTitle.split(", ");

        textareaContent = $('#message_name').val();
        $('#message_name').val(textareaContent + imageTitle[1]);
    });

    $("#showSmiles").on('click', function() {
        if ($("#emoticonsZone").is(':visible')) {
            $("#emoticonsZone").hide();
        } else {
            $("#emoticonsZone").show();
        }
    });
});