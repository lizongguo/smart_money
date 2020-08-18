var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('kanae');
var userDao = require('../dataFactory/userDao');
var groupDao = require('../dataFactory/groupDao');
var messageDao = require('../dataFactory/messageDao');
var friendDao = require('../dataFactory/friendDao');
var specsDao = require('../dataFactory/specsDao');
var ngDao = require('../dataFactory/ngDao');
var utils = require('../utils/utils');
var tips = require('../utils/tips');
var servicePush = require('../utils/servicePush');
var sprintf = require('sprintf').sprintf;
var async = require('async');

var redisClient = kanae.app.get('redisClient');
var redisKeys = kanae.app.get('redisKeys');
var uidHash = utils.genKey(redisKeys['USER_CLIENT_ID_HASH']);
var msgListQueue = utils.genKey(redisKeys['MSG_LIST_QUEUE']);
var chatMsgListQueue = utils.genKey(redisKeys['CHAT_MSG_LIST_QUEUE']);


var app = kanae.app;

var Message = module.exports;

Message.init = function (session, data, conn, next) {
    data.desk_id = session.desk_id;
    
    if(!data.md){
        utils.invokeCallback(next, 'md data is not found.', null);
        return;
    }
    
    var onlineHash = utils.genKey(sprintf(redisKeys['DESKS_ONLINE_USER_HASH'], session.desk_id));
    async.waterfall(
    [
        function(cb){
            groupDao.getDeskInfoByDeskId(data.desk_id, function(err, desk){
                if(err || !desk.hasOwnProperty("id")){
                    utils.invokeCallback(cb, 'desk is not found.', null);
                    return;
                }
                utils.invokeCallback(cb, null, desk);
            });
        },
        function(desk, cb){
            redisClient.hgetall(onlineHash, function (err, members) {
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                var memArr = [];
                var i=0;
                for(var key in members){
                    memArr[i] = members[key];
                    i++;
                }
                utils.invokeCallback(cb, null, {"desk": desk, "members":memArr});
            });
        },
        function(result, cb){  //分发消息
            var msg = data.md;
            msg.user_id = session.user_id;
            
            /**
             * 添加商品到数据队列中
             */
            specsDao.dealDeskGoods(session.desk_id, data.md);
            result.members.forEach(function(uid){
                if(uid.toString() !== session.user_id.toString()){
                    userDao.getUserClientServer(uid, function(err, sid){
                        if(err){
                            return;
                        }
                        if(!!sid){
                            if(sid === app.getServerId()){
                                messageDao.pushLocalMsg(msg, uid, function(err, rs){
                                    if(err){
                                        userDao.delUserClientServer(uid);
                                    }
                                    return ;
                                });
                            }else{
                                //分发远程服务器push消息。
                                var d = {'route':'m','d':{'uid':uid, 'md': msg}};
                                servicePush.push(sid, d);
                            }
                        }
                    });
                }
            });
            utils.invokeCallback(cb, null, msg);
        }
    ],
    function(err, result){
        if(err){
            utils.invokeCallback(next, err, null);
            return;
        }
        utils.invokeCallback(next, null, result);
    });
};

Message.pushInit = function (uid, data, next) {
    if(!data.md){
        utils.invokeCallback(next, 'message data is not found', null);
        return;
    }
    userDao.getProfile(uid, function (err, user) {
        if (err) {
            utils.invokeCallback(next, err, null);
            return;
        }
        if (!user.hasOwnProperty("id")) {
            utils.invokeCallback(next, 'user is not found.', null);
            return;
        }
        
        var session_id = app.getSessionId(uid);
        if (!session_id) {
            utils.invokeCallback(next, 'userid:' + uid + ' binding session_id is not found', null);
            return;
        }
        var session  = app.getSession(session_id);
        if(!!!session || !!!session.client){
            app.delSessionId(uid);
            utils.invokeCallback(next, 'userid:' + uid + ' binding session is not found', null);
            return;
        }
        var pushData = {"route":"m", "status": true, "error":"", "d":data.md};
        
        session.client.send(JSON.stringify(pushData));
        logger.info("push user(user_id:%j) event(id:%j) specs(id:%j, num:%j) success.", uid, data.md.event, data.md.goods_specs_id, data.md.goods_num);
        utils.invokeCallback(next, null, true);
    });
};

Message.allDeskGoods = function (session, data, conn, next) {
    data.desk_id = session.desk_id;
    
    var deskGoodsHash = utils.genKey(sprintf(redisKeys['DESKS_GOODS_HASH'], session.desk_id));
    var specsCategoryHash = utils.genKey(redisKeys['SPECS_CATEGORY_HASH']);
    async.waterfall(
    [
        function(cb){
            specsDao.getSpecsCategory(function (err, map) {
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                utils.invokeCallback(cb, null, {"specsMap": map});
            });
        },
        function(d, cb){
            specsDao.getDeskGoodsHash(session.desk_id, d.specsMap, function (err, goodsArr) {
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                utils.invokeCallback(cb, null, goodsArr);
            });
        }
    ],
    function(err, result){
        if(err){
            utils.invokeCallback(next, err, null);
            return;
        }
        utils.invokeCallback(next, null, result);
    });
};

Message.clearDeskGoods = function (session, data, conn, next) {
    data.desk_id = session.desk_id;
    
    var onlineHash = utils.genKey(sprintf(redisKeys['DESKS_ONLINE_USER_HASH'], session.desk_id));
    async.waterfall(
    [
        function(cb){
            specsDao.clearDeskGoods(data.desk_id, function(err, res){
                if(err){
                    utils.invokeCallback(cb, '清除购物车成功', null);
                    return;
                }
                utils.invokeCallback(cb, null, true);
            });
        },
        function(result, cb){
            redisClient.hgetall(onlineHash, function (err, members) {
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                var memArr = [];
                var i=0;
                for(var key in members){
                    memArr[i] = members[key];
                    i++;
                }
                utils.invokeCallback(cb, null, {"members": memArr});
            });
        },
        function(result, cb){  //分发消息
            var msg = {user_id:session.user_id};
            result.members.forEach(function(uid){
                if(uid.toString() !== session.user_id.toString()){
                    userDao.getUserClientServer(uid, function(err, sid){
                        if(err){
                            return;
                        }
                        if(!!sid){
                            if(sid === app.getServerId()){
                                messageDao.pushLocalClearMsg(msg, uid, function(err, rs){
                                    if(err){
                                        userDao.delUserClientServer(uid);
                                    }
                                    return ;
                                });
                            } else {
                                //分发远程服务器push消息。
                                var d = {'route':'c', 'd': {'uid':uid, msg: msg}};
                                servicePush.push(sid, d);
                            }
                        }
                    });
                }
            });
            utils.invokeCallback(cb, null, msg);
        }
    ],
    function(err, result){
        if(err){
            utils.invokeCallback(next, err, null);
            return;
        }
        utils.invokeCallback(next, null, result);
    });
};

Message.pushClearDeskGoods = function (uid, data, next) {
    if(!data.msg){
        utils.invokeCallback(next, 'message data is not found', null);
        return;
    }
    userDao.getProfile(uid, function (err, user) {
        if (err) {
            utils.invokeCallback(next, err, null);
            return;
        }
        if (!user.hasOwnProperty("id")) {
            utils.invokeCallback(next, 'user is not found.', null);
            return;
        }
        
        var session_id = app.getSessionId(uid);
        if (!session_id) {
            utils.invokeCallback(next, 'userid:' + uid + ' binding session_id is not found', null);
            return;
        }
        var session  = app.getSession(session_id);
        if(!!!session || !!!session.client){
            app.delSessionId(uid);
            utils.invokeCallback(next, 'userid:' + uid + ' binding session is not found', null);
            return;
        }
        var pushData = {"route":"c", "status": true, "error":"", "d":data.msg};
        
        session.client.send(JSON.stringify(pushData));
        logger.info("push user(user_id:%j) senduid(id:%j) success.", uid, data.msg.user_id);
        utils.invokeCallback(next, null, true);
    });
};
