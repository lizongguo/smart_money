var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('ordering');
var userDao = module.exports;
var redisClient = kanae.app.get('redisClient');
var dbclient = kanae.app.get('dbclient');
var redisKeys = kanae.app.get('redisKeys');
var utils = require('../utils/utils');
var async = require('async');
var sprintf = require('sprintf').sprintf;
var userClientKey = utils.genKey(redisKeys['USER_CLIENT_ID_HASH']);

userDao.getProfile = function(userId, cb) {
    var key = sprintf(redisKeys['USER_PROFILE_HASH'], userId);
    var profileKey = utils.genKey(key);
    var getFromRedis = function() {
        redisClient.hgetall(profileKey, function(err, user){
            if (err) {
                utils.invokeCallback(cb, err, null);
                return;
            }
            if (user) {
                utils.invokeCallback(cb, null, user);
                return;
            }
            utils.invokeCallback(getFromDB);
        });
    }
    var getFromDB = function() {
        var sql = "SELECT u.id, u.username, u.role, u.avatar, u.phone, u.remark, u.deleted \
                   FROM users u \
                   WHERE deleted=0 and u.id = ?";
        var args = [userId];
        dbclient.query(sql, args, function(err, res) {
            if (err) {
                utils.invokeCallback(cb, err, null);
                return;
            }
            if ( !!res && res.length === 1) {
                var rs = res[0];
                redisClient.hmset(profileKey, rs);
                cb(null, rs);
                return;
            }
            utils.invokeCallback(cb, null, []);
        });
    }
    getFromRedis();
};

userDao.getUserClientServer = function(userId, cb) {
    var userHashKey = sprintf(redisKeys['USER_CLIENT_ID_HASH_KEY'], userId);
    redisClient.hget(userClientKey, userHashKey, function (err, sid) {
        if(err){
            utils.invokeCallback(cb, err, null);
            return;
        }
        utils.invokeCallback(cb, null, sid);
    });
};

userDao.delUserClientServer = function(userId, cb) {
    var userHashKey = sprintf(redisKeys['USER_CLIENT_ID_HASH_KEY'], userId);
    redisClient.hdel(userClientKey, userHashKey, function (err, flg) {
        if (err) {
            utils.invokeCallback(cb, err, null);
            return;
        }
        utils.invokeCallback(cb, null, true);
    });
};

userDao.getUserIdByToken = function (token, cb) {
    var sql = "SELECT user_id \
               FROM user_tokens  \
               WHERE access_token = ?";
    var args = [token];
    dbclient.query(sql, args, function(err, res) {
        if (err) {
            utils.invokeCallback(cb, err, null);
            return;
        }
        console.log(res);
        if ( !!res && res.length === 1) {
            var rs = res[0];
            utils.invokeCallback(cb, null, rs['user_id']);
            return;
        }
        utils.invokeCallback(cb, null, 0);
    });
};

