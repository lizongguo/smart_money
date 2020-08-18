var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('kanae');
var friendDao = module.exports;
var redisClient = kanae.app.get('redisClient');
var dbClient = kanae.app.get('dbclient');
var redisKeys = kanae.app.get('redisKeys');
var utils = require('../utils/utils');
var async = require('async');
var sprintf = require('sprintf').sprintf;
var chatUidHash = utils.genKey(redisKeys['CHAT_USER_CLIENT_ID_HASH']);

friendDao.getFriendsByUid = function(userId, cb) {
    var key = sprintf(redisKeys['USER_FRIENDS_KEY'], userId);
    var friendsKey = utils.genKey(key);
    redisClient.exists(friendsKey, function(err, res) {
        if (res) {
            redisClient.get(friendsKey,function(err,data){
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                var members = data.split(',');
                utils.invokeCallback(cb, null, members);
                return;
            });
        } else {
            var sql = "SELECT group_concat(friend_user_id order by fid desc) as friend_ids FROM kt_friends WHERE user_id = ? AND deleted = 0";
            var args = [userId];
            dbClient.query(sql, args, function(err, res) {
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                var members = [];
                if (!!res && res.length == 1) {
                    if(res[0].friend_ids){
                        var members = res[0].friend_ids.toString().split(',');
                        if (members.length > 0) {
                            redisClient.set(friendsKey,members.join(','));
                        }
                    }
                }
                utils.invokeCallback(cb, null, members);
            });
        }
    });
};

friendDao.getUserClientServer = function(userId, cb) {
    var userHashKey = sprintf(redisKeys['CHAT_USER_CLIENT_ID_HASH_KEY'], userId);
    redisClient.hget(chatUidHash, userHashKey, function (err, sid) {
        if(err){
            utils.invokeCallback(cb, err, null);
            return;
        }
        utils.invokeCallback(cb, null, sid);
    });
};

friendDao.delUserClientServer = function(userId, cb) {
    var userHashKey = sprintf(redisKeys['CHAT_USER_CLIENT_ID_HASH_KEY'], userId);
    redisClient.hdel(chatUidHash, userHashKey, function (err, flg) {
        if (err) {
            utils.invokeCallback(cb, err, null);
            return;
        }
        utils.invokeCallback(cb, null, true);
    });
};



