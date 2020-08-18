var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('ordering');

var groupDao = module.exports;
var userDao = require('./userDao');
var redisClient = kanae.app.get('redisClient');
var dbClient = kanae.app.get('dbclient');
var redisKeys = kanae.app.get('redisKeys');
var utils = require('../utils/utils');
var async = require('async');
var sprintf = require('sprintf').sprintf;

groupDao.getDeskInfoByDeskId = function(desk_id, cb) {
    if(!desk_id || !(/^[0-9]{1,}$/.test(desk_id))) {
        utils.invokeCallback(cb, 'desk_id error.', null);
        return ;
    }
    var key = utils.genKey(sprintf(redisKeys['DESKS_INFO_HASH'], desk_id));
    var getFromRedis = function() {
        redisClient.hgetall(key, function(err, desk) {
            if (err || !desk) {
                utils.invokeCallback(getFromDB);
                return;
            }
            utils.invokeCallback(cb, err, desk);
        });
    };
    var getFromDB = function() {
        var sql = "SELECT id, shop_id, type_id, alias, number, state FROM desks WHERE id = ? AND deleted = 0";
        var args = [desk_id];
        dbClient.query(sql, args, function(err, res) {
            if (err) {
                utils.invokeCallback(cb, err, null);
                return;
            }
            console.log(res);
            if (!!res && res.length > 0) {
                var desk = res[0];
                redisClient.hmset(key, desk);
                utils.invokeCallback(cb, null, desk);
                return;
            }
            utils.invokeCallback(cb, 'desk is not found', null);
        });
    };
    getFromRedis();
};
