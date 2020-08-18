var net = require('net');
var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('kanae');
var userDao = require('../dataFactory/userDao');
var utils = require('../utils/utils');
var sprintf = require('sprintf').sprintf;
var async = require('async');

var redisClient = kanae.app.get('redisClient');
var redisKeys = kanae.app.get('redisKeys');
var uidHash = utils.genKey(redisKeys['USER_CLIENT_ID_HASH']);
var app = kanae.app;

var servicePush = module.exports;

servicePush.push = function (serverId, data, cb) {
    var server = app.getServerById(serverId);
    logger.info(server);
    if(!server){
        utils.invokeCallback(cb, null, true);
        return false;
    }
    var client = new net.Socket();
    client.connect(server.clientPort, server.clientHost, function() {
        logger.info('connected to server('+serverId+'): ' + server.clientHost + ':' + server.clientPort);
        //发送信息。
        logger.info('Push Data: ' + JSON.stringify(data));
        client.write(JSON.stringify(data));
        client.on('close', function() {
            client.destroy();
            logger.info('Connection server('+serverId+') closed.');
            utils.invokeCallback(cb, null, true);
        });
    });
    return true;
};
