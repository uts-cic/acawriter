/**
 * File : Node server to list and emit sockets
 * Created By : Developer CIC, Radhika Mogarkar
 * Date: 30/08/2017
 **/

var app     = require('express')();
var http    = require('http').Server(app);
var io      = require('socket.io')(http);
var Redis   = require('ioredis');
var redis   = new Redis();


http.listen(3000,function(){
   console.log('Listening on port 3000');
});


// subscribe to various channels here

    redis.subscribe('operational-log',function(){

    });


redis.on('message', function(channel, message){
    console.log('Message Received: '+message);

    message = JSON.parse(message);
    io.emit(channel +":"+message.event, message.data);
});

