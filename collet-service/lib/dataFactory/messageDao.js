var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('kanae');
var redisClient = kanae.app.get('redisClient');
var dbclient = kanae.app.get('dbclient');
var redisKeys = kanae.app.get('redisKeys');
var utils = require('../utils/utils');
var async = require('async');
var sprintf = require('sprintf').sprintf;
var msgMaxIdKey = utils.genKey(redisKeys['MSG_MAX_ID_KEY']);
var chatMsgMaxIdKey = utils.genKey(redisKeys['CHAT_MSG_MAX_ID_KEY']);
var app = kanae.app;

var messageDao = module.exports;

messageDao.pushLocalMsg = function(msg, toUserId, cb) {
    var session_id = app.getSessionId(toUserId);
    if(!session_id){
        utils.invokeCallback(cb, 'is not found user:'+toUserId + " session in " + app.getServerId() + " server.", null);
        return;
    }
    var session = app.getSession(session_id);
    if(!session){
        utils.invokeCallback(cb, 'is not found user:'+toUserId + " session in " + app.getServerId() + " server.", null);
        return;
    }
    var backData = {'route' : 'm', 'status': true, 'error': '', 'd': msg};
    session.client.send(JSON.stringify(backData));
    utils.invokeCallback(cb, null, true);
};

/**
 * 本地推送 清除购物车消息
 * @param {type} msg
 * @param {type} toUserId
 * @param {type} cb
 * @returns {undefined}
 */
messageDao.pushLocalClearMsg = function(msg, toUserId, cb) {
    var session_id = app.getSessionId(toUserId);
    if(!session_id){
        utils.invokeCallback(cb, 'is not found user:'+toUserId + " session in " + app.getServerId() + " server.", null);
        return;
    }
    var session = app.getSession(session_id);
    if(!session){
        utils.invokeCallback(cb, 'is not found user:'+toUserId + " session in " + app.getServerId() + " server.", null);
        return;
    }
    var backData = {'route' : 'c', 'status': true, 'error': '', 'd': msg};
    session.client.send(JSON.stringify(backData));
    utils.invokeCallback(cb, null, true);
};