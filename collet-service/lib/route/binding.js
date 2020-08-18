var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('ordering');
var userDao = require('../dataFactory/userDao');
var groupDao = require('../dataFactory/groupDao');
var friendDao = require('../dataFactory/friendDao');
var utils = require('../utils/utils');
var servicePush = require('../utils/servicePush');
var sprintf = require('sprintf').sprintf;
var async = require('async');

var redisClient = kanae.app.get('redisClient');
var redisKeys = kanae.app.get('redisKeys');
var uidHash = utils.genKey(redisKeys['USER_CLIENT_ID_HASH']);
var chatUidHash = utils.genKey(redisKeys['CHAT_USER_CLIENT_ID_HASH']);
var app = kanae.app;

var binding = module.exports;

//绑定用户
binding.init = function (token, desk_id, conn, next) {
    var onlineHash = utils.genKey(sprintf(redisKeys['DESKS_ONLINE_USER_HASH'], desk_id));
    async.waterfall(
            [
                function (cb) {
                    userDao.getUserIdByToken(token, function (err, uid) {
                        logger.info(err, uid);
                        if (err || uid == 0) {
                            utils.invokeCallback(cb, 'token is not found', null);
                            return;
                        }
                        utils.invokeCallback(cb, null, uid);
                    });
                },
                function (uid, cb) {
                    groupDao.getDeskInfoByDeskId(desk_id, function (err, desk) {
                        if (err || !desk.hasOwnProperty("id")) {
                            utils.invokeCallback(cb, 'desk is not found.', null);
                            return;
                        }
                        var userHashKey = sprintf(redisKeys['USER_CLIENT_ID_HASH_KEY'], uid);
                        utils.invokeCallback(cb, null, {'uid': uid, 'desk': desk, 'userHashKey': userHashKey});
                    });
                },
                function (data, cb) {
                    userDao.getProfile(data.uid, function (err, user) {
                        if (err) {
                            utils.invokeCallback(cb, err, null);
                            return;
                        }
                        if (!user.hasOwnProperty("id")) {
                            utils.invokeCallback(cb, 'user is not found.', null);
                            return;
                        }
                        
                        data.user = user;
                        utils.invokeCallback(cb, null, data);
                    });
                },
                function (data, cb) {
                    redisClient.hget(uidHash, data.userHashKey, function (err, sid) {
                        if (err) {
                            utils.invokeCallback(cb, err, null);
                            return;
                        }
                        if (!!sid) {
                            var delRedisMap = function (cb2) {
                                redisClient.hdel(uidHash, data.userHashKey, function (err, flg) {
                                    if (err) {
                                        utils.invokeCallback(cb2, err, null);
                                        return;
                                    }
                                    utils.invokeCallback(cb2, null, true);
                                });
                            };
                            var delUserOnline = function (backrs, cb2) {
                                var onlineKey = sprintf(redisKeys['DESKS_ONLINE_USER_KEY'], data.uid);
                                redisClient.hdel(onlineHash, onlineKey, function (err, flg) {
                                    if (err) {
                                        utils.invokeCallback(cb2, err, null);
                                        return;
                                    }
                                    utils.invokeCallback(cb2, null, true);
                                });
                            };
                            var delUserSession = function (backrs, cb2) {
                                console.log('sid:' + sid + "\r\n");
                                if (sid === app.getServerId()) {
                                    var session_id = app.getSessionId(data.uid);
                                    if (!session_id) {
                                        utils.invokeCallback(cb2, null, true);
                                        return ;
                                    }
                                    
                                    var currentSession = app.getSession(session_id);
                                    app.delSessionId(data.uid);
                                    app.delSession(session_id);
                                    
                                    console.log('delete old session is success');
                                    
                                    if (!currentSession) {
                                        utils.invokeCallback(cb2, null, true);
                                        return ;
                                    }
                                    var groupOnlineKey = sprintf(redisKeys['DESKS_ONLINE_USER_KEY'], data.uid);
                                    var groupOnlineHash = utils.genKey(sprintf(redisKeys['DESKS_ONLINE_USER_HASH'], currentSession.desk_id));
                                    redisClient.hdel(groupOnlineHash, groupOnlineKey, function (err, flg) {
                                        if (err) {
                                            logger.info("del old desk online map failure.");
                                        }else{
                                            logger.info("del old desk online map success.");
                                        }
                                        utils.invokeCallback(cb2, null, true);
                                    });
                                    
                                } else {
                                    //删除远程服务器上的conn
                                    var d = {'route': 'b', 'd': {'uid': data.uid}};
                                    servicePush.push(sid, d, function(err, flg){
                                        utils.invokeCallback(cb2, null, true);
                                    });
                                }
                            };
                            async.waterfall(
                            [
                                delRedisMap, //删除该用户当前存在的map表
                                delUserOnline, //删除用户之前所在desk的map表
                                delUserSession  //删除已经存在的session ，注销长连接。
                            ],
                            function (err, rs) {
                                if (err) {
                                    utils.invokeCallback(cb, err, null);
                                    return;
                                }
                                utils.invokeCallback(cb, null, data);
                            });
                        } else {
                            utils.invokeCallback(cb, null, data);
                        }
                    });
                },
                function (data, cb) {
                    //添加新hash表
                    var onlineKey = sprintf(redisKeys['DESKS_ONLINE_USER_KEY'], data.uid);
                    redisClient.hset(onlineHash, onlineKey, data.uid);
                    redisClient.hset(uidHash, data.userHashKey, app.getServerId());
                    app.addSession(data.uid, conn, desk_id);
                    conn.name = app.getSessionId(data.uid);
                    utils.invokeCallback(cb, null, data);
                }
            ], function (err, data) {
                if (err) {
                    utils.invokeCallback(next, err, null);
                    return;
                }
                else {
                    utils.invokeCallback(next, null, {"sid": app.getSessionId(data.uid)});
                }
    });
};

//解绑用户。
binding.unInit = function (uid, cb) {
    userDao.getProfile(uid, function (err, user) {
        if (err) {
            utils.invokeCallback(cb, err, null);
            return;
        }
        if (!user.hasOwnProperty("id")) {
            utils.invokeCallback(cb, 'user is not found.', null);
            return;
        }
        var session_id = app.getSessionId(uid);
        if (!session_id) {
            utils.invokeCallback(cb, null, true);
            return ;
        }
        var session = app.getSession(session_id);
        if (!(session)) {
            utils.invokeCallback(cb, null, true);
            return ;
        }
        //删除用户之前所在group的map表
        var onlineHash = utils.genKey(sprintf(redisKeys['DESKS_ONLINE_USER_HASH'], session.desk_id));
        var onlineKey = sprintf(redisKeys['DESKS_ONLINE_USER_KEY'], uid);

        app.delSessionId(uid);
        app.delSession(session_id);

        redisClient.hdel(onlineHash, onlineKey, function (err, flg) {
            if (err) {
                logger.info("del old group online map failure.");
            } else {
                logger.info("del old group online map seccess.");
            }
            utils.invokeCallback(cb, null, true);
            return;
        });
    });
};


