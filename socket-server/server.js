/**
 * File : Node server to list and emit sockets
 * Created By : Developer CIC, Radhika Mogarkar
 * Date: 30/08/2017
 **/

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');

var pwd = process.env.REDIS_PASSWORD;

var redis = new Redis({
    port: process.env.REDIS_PORT,
    host: process.env.REDIS_HOST,
    password: pwd && pwd !== 'null' ? pwd : null
});

http.listen(3000, function () {
    console.log('Listening on port 3000');
});

redis.subscribe('operational-log', function (e, dd) {

});

redis.subscribe('private-dashboard', function (err, d) {

});

redis.subscribe('private-user-activity', function (err, r) {

});

redis.on('message', function (channel, message) {
    console.log('Channel Received: ' + channel);
    message = JSON.parse(message);
    io.emit(channel + ":" + message.event, message.data);
});
