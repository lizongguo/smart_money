var kanae = require('./kanae');
var logger = require('./logger/logger').getLogger('kanae');
var userDao = require('./dataFactory/userDao');
var groupDao = require('./dataFactory/groupDao');
var utils = require('./utils/utils');
var sprintf = require('sprintf').sprintf;
var async = require('async');
var binding = require('./route/binding');
var message = require('./route/message');
var app = kanae.app;

var redisClient = kanae.app.get('redisClient');
var redisKeys = kanae.app.get('redisKeys');
var userIdHash = utils.genKey(redisKeys['USER_CLIENT_ID_HASH']);
var chatUserIdHash = utils.genKey(redisKeys['CHAT_USER_CLIENT_ID_HASH']);
var exp = module.exports;

exp.routeUtil = function (route, data, conn, cb) {
    switch (route) {
        case 'b': //绑定用户
            var token = data.token;
            var desk_id = data.desk_id;
            if (!token || !desk_id) {
                utils.invokeCallback(cb, 'token or desk_id is not found.', null);
                return;
            }
            binding.init(token, desk_id, conn, cb);
            break;
        case 'm': //发送消息
            var sid = data.sid;
            var session = app.getSession(sid);
            if(!session){
                utils.invokeCallback(cb, 'session is not found.', null);
                return;
            }
            message.init(session, data, conn, cb);
            break;
        case 'a': //获取当前桌位的购物车情报
            var sid = data.sid;
            var session = app.getSession(sid);
            if(!session){
                utils.invokeCallback(cb, 'session is not found.', null);
                return;
            }
            message.allDeskGoods(session, data, conn, cb);
            break;
        case 'c': //清除当前桌位的购物车
            var sid = data.sid;
            var session = app.getSession(sid);
            if(!session){
                utils.invokeCallback(cb, 'session is not found.', null);
                return;
            }
            message.clearDeskGoods(session, data, conn, cb);
            break;
        case 'p': //ping
            utils.invokeCallback(cb, null, 'ok');
            break;
        default :
            utils.invokeCallback(cb, 'route is not found.', null);
            return;
    }
};

exp.pushRouteUtil = function(route, data, cb) {
    var userId = data.uid;
    if (!userId) {
        utils.invokeCallback(cb, 'user id is not found.', null);
        return;
    }
	switch (route) {
        case 'b': //解绑定用户
            binding.unInit(userId, cb);
            break;
        case 'm': //发送消息
            message.pushInit(userId, data, cb);
        case 'c': //发送消息
            message.pushLocalClearMsg(userId, data, cb);
            break;
        default :
            utils.invokeCallback(cb, 'route is not found.', null);
            return;
    }
};

exp.destroyClient = function (conn, cb) {
    var session_id = conn.name;
    logger.info('client is close, binding user(session_id:' + session_id + ').');
    if (session_id) {
        var session = app.getSession(session_id);
        var user_id = session.user_id;
        if (!!session && !!user_id) {
            console.log(session);
            binding.unInit(user_id, cb);
            return;
        }
        app.delSession(session_id);
    }
    utils.invokeCallback(cb, null, true);
};

