var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

//app.get('/', function(req, res){
//    res.sendFile(__dirname + '/index.html');
//});

io.on('connection', function(socket){
    socket.on('check_message', function(msg){
        io.emit('check_message', msg);
    });
});

http.listen(3000, function(){
    console.log('listening on *:3000');
});

//var app = require('http').createServer().listen(3000);
//
//var mysql      = require('mysql');
//var connection = mysql.createConnection({
//    host     : 'localhost',
//    user     : 'root',
//    password : '',
//    database: 'forum'
//});
//
//connection.connect();
//
//console.log('Server running at http://127.0.0.1:3000/');
//
//var io = require('socket.io').listen(app);
//
//var prev_id = 0;
//
//io.sockets.on('connection', function (socket) {
//    console.log("bbb");
////    socket.emit('check_messages', 'Hello');
//    socket.on('check_messages', function() {
//        var q = "SELECT * FROM notification";
//        console.log("aaa");
//        connection.query(q, function(err, rows, fields) {
//            if (err) throw err;
//            if (rows[0].id > prev_id){
//                socket.emit('new_message',rows[0]);
//                prev_id = rows[0].id
//            }
//        });
//    });
//});