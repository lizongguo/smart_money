var kanae = require('../kanae');
var logger = require('../logger/logger').getLogger('ordering');
var specsDao = module.exports;
var redisClient = kanae.app.get('redisClient');
var dbClient = kanae.app.get('dbclient');
var redisKeys = kanae.app.get('redisKeys');
var utils = require('../utils/utils');
var async = require('async');
var sprintf = require('sprintf').sprintf;
var specsCategoryHash = utils.genKey(redisKeys['SPECS_CATEGORY_HASH']);

specsDao.getSpecsCategory = function(cb) {
    var mapKey = specsCategoryHash;
    redisClient.exists(mapKey, function(err, res) {
        if (res) {
            redisClient.hgetall(mapKey, function(err,data){
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                utils.invokeCallback(cb, null, data);
                return;
            });
        } else {
            var sql = "SELECT goods_specs.id, category_id  FROM goods_specs JOIN goods on goods.id=goods_id  where goods_specs.deleted=0 and goods.deleted=0";
            var args = [];
            dbClient.query(sql, args, function(err, res) {
                if (err) {
                    utils.invokeCallback(cb, err, null);
                    return;
                }
                var map = {};
                res.forEach(function(item, index){
                    map[item.id] = item.category_id;
                });
                redisClient.hmset(mapKey, map);
                utils.invokeCallback(cb, null, map);
            });
        }
    });
};


specsDao.getDeskGoodsHash = function(desk_id, specsMap, cb){
    var deskGoodsHash = utils.genKey(sprintf(redisKeys['DESKS_GOODS_HASH'], desk_id));
    redisClient.hgetall(deskGoodsHash, function (err, goods) {
        if (err) {
            utils.invokeCallback(cb, null, []);
            return;
        }
        var goodsArr = [];
        
        if (!!goods) {
            for (let key in goods) {
                if (goods[key] <= 0) {
                    continue;
                }
                var d = {
                    goods_specs_id: key, 
                    goods_num: goods[key], 
                    category_id: !!specsMap[key] ? specsMap[key] : 0
                };
                goodsArr.push(d);
            }
        }
        utils.invokeCallback(cb, null, goodsArr);
    });
};

//清除redis 购物车
specsDao.clearDeskGoods = function(desk_id, cb) {
    var deskGoodsHash = utils.genKey(sprintf(redisKeys['DESKS_GOODS_HASH'], desk_id));
    redisClient.del(deskGoodsHash);
    utils.invokeCallback(cb, null, true);
};

//对 购物车中 商品进行增减 操作
specsDao.dealDeskGoods = function(desk_id, md, cb) 
{
    var deskGoodsHash = utils.genKey(sprintf(redisKeys['DESKS_GOODS_HASH'], desk_id));
    var num = ((md.event == 'add') ?  1 : -1) * parseInt(md.goods_num);
    redisClient.hincrby(deskGoodsHash, md.goods_specs_id, num);
    utils.invokeCallback(cb, null, true);
};
