var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('kanae');
var userDao = require('../dataFactory/userDao');
var groupDao = require('../dataFactory/groupDao');
var messageDao = require('../dataFactory/messageDao');
var friendDao = require('../dataFactory/friendDao');
var utils = require('../utils/utils');
var servicePush = require('../utils/servicePush');
var sprintf = require('sprintf').sprintf;
var async = require('async');

var redisClient = kanae.app.get('redisClient');
var redisKeys = kanae.app.get('redisKeys');
var uidHash = utils.genKey(redisKeys['USER_CLIENT_ID_HASH']);
var msgListQueue = utils.genKey(redisKeys['MSG_LIST_QUEUE']);
var chatMsgListQueue = utils.genKey(redisKeys['CHAT_MSG_LIST_QUEUE']);
var sysConfig = kanae.app.get('sysConfig');

var app = kanae.app;

var tips = module.exports;

tips.init = function (group_id, next) {
    var base = {gid:group_id, mt:"0"};
    var onlineHash = utils.genKey(sprintf(redisKeys['GROUPS_ONLINE_USER_HASH'], base.gid));
    var tipQueue = utils.genKey(sprintf(redisKeys['GROUP_TIPS_QUEUE'], base.gid));
    console.log(tipQueue);
    async.waterfall(
    [
        function(cb){
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
                utils.invokeCallback(cb, null, {"members":memArr});
            });
        },
        function(result, cb){
            var msgArr = [];
            redisClient.lrange(tipQueue, 0, -1, function (err, response){
                if(err){
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                var i = 0;
                for(var x in response ) {
                    var tips = (response[x]).toString().split(":");
                    msgArr[i] = {
                        "uid" : "0",
                        "uuid" : "0",
                        "mid" : "0",
                        "allmaxid" : "0",
                        "gid" : ""+ base . gid,
                        "mt" : "0",
                        "c" : sprintf(sysConfig.tips[tips[0]], tips[1]),
                        "ct" : ""+utils.currentTime()
                    };
                    i++;
                    //删除当前操作的队列值
                    redisClient.lrem(tipQueue, 0, response[x], function(cc){});
                }
                result.msgArr = msgArr;
                utils.invokeCallback(cb, null, result);
            });
        },
        function (result, cb){
            result.msgArr.forEach(function(msg){
                var currentMsg = msg;
                async.waterfall(
                [
                    function(cb2){  //获取 group max message_id
                        messageDao.getGroupMaxId(base.gid, function(err, maxid){
                            if(err){
                                utils.invokeCallback(cb2, err, null);
                                return;
                            }
                            logger.info('group_id(' + base.gid + ') tipsmax_id:'+ maxid);
                            currentMsg.mid = maxid;
                            utils.invokeCallback(cb2, null, currentMsg);
                        });
                    },
                    function(currentMsg, cb2){  //获取 all max message_id
                        messageDao.getMaxId(function(err, maxid){
                            if(err){
                                utils.invokeCallback(cb2, err, null);
                                return;
                            }
                            logger.info('all_max_id:'+ maxid);
                            currentMsg.allmaxid = maxid;
                            utils.invokeCallback(cb2, null, currentMsg);
                        });
                    },
                    function(currentMsg, cb2){  //保存数据
                        
                        var msgInfoHash = utils.genKey(sprintf(redisKeys['MSG_INFO_HASH'], currentMsg.allmaxid));

                        redisClient.hmset(msgInfoHash, currentMsg, function (err, rs){
                            if(err){
                                utils.invokeCallback(cb2, 'redis:set msg info failure.', null);
                                return;
                            }
                            redisClient.lpush(msgListQueue, currentMsg.allmaxid, function(err, rs){
                                if(err){
                                    utils.invokeCallback(cb2, 'redis:set msg to queue failure.', null);
                                    return;
                                }
                                utils.invokeCallback(cb2, null, currentMsg);
                            });
                        });
                    },
                    function(currentMsg, cb2){  //分发消息
                        var msg = currentMsg;
                        var allMaxId = currentMsg.allmaxid;
                        result.members.forEach(function(uid){
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
                                        var d = {'route':'m','d':{'uid':uid, 'mid': allMaxId}};
                                        servicePush.push(sid, d);
                                    }
                                }
                            });
                        });
                        utils.invokeCallback(cb2, null, msg);
                    }
                ],
                function(err, result){
                    if(err){
                        console.log('push tips is err.');
                        return;
                    }
                    console.log('push tips is success.');
                });
            });
            utils.invokeCallback(cb, null, true);
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

