var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('kanae');
var ngDao = module.exports;
var redisClient = kanae.app.get('redisClient');
var dbClient = kanae.app.get('dbclient');
var redisKeys = kanae.app.get('redisKeys');
var utils = require('../utils/utils');
var async = require('async');
var sprintf = require('sprintf').sprintf;
var groupNgKey = utils.genKey(redisKeys['GROUP_NG_KEYWORD']);
var groupNgMsg = utils.genKey(redisKeys['GROUP_NG_MSG']);

ngDao.getNgKeyword = function(cb) {
    redisClient.exists(groupNgKey, function(err, res) {
        if (res) {
            redisClient.get(groupNgKey,function(err,data){
                if (err) {
                    utils.invokeCallback(cb, null, []);
                    return;
                }
                var keyword = data.split('|');
                utils.invokeCallback(cb, null, keyword);
                return;
            });
        } else {
            var sql = "SELECT group_concat(keyword SEPARATOR '|') as keywords FROM kt_ng_keyword";
            var args = [];
            dbClient.query(sql, args, function(err, res) {
                if (err) {
                    utils.invokeCallback(cb, null, []);
                    return;
                }
                var keyword = [];
                if (!!res && res.length == 1) {
                    if(res[0].keywords){
                        var keyword = res[0].keywords.toString().split('|');
                        if (keyword.length > 0) {
                            redisClient.set(groupNgKey, keyword.join('|'));
                        }
                    }
                }
                utils.invokeCallback(cb, null, keyword);
            });
        }
    });
};

ngDao.getNgMsg = function(cb) {
    redisClient.exists(groupNgMsg, function(err, res) {
        if (res) {
            redisClient.get(groupNgMsg,function(err, data){
                if (err) {
                    utils.invokeCallback(cb, null, '文明チャットをしてください。');
                    return;
                }
                utils.invokeCallback(cb, null, data);
                return;
            });
        } else {
            var sql = "SELECT value as val FROM kt_setting where `key`= ? limit 1";
            var args = ['ng_msg'];
            dbClient.query(sql, args, function(err, res) {
                var msg = "文明チャットをしてください。";
                if (err) {
                    utils.invokeCallback(cb, null, msg);
                    return;
                }
                if (!!res && res.length > 0) {
                    if(res[0].val){
                        msg = res[0].val.toString();
                        redisClient.set(groupNgMsg, msg);
                    }
                }
                utils.invokeCallback(cb, null, msg);
            });
        }
    });
};





