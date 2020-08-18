var http = require('http');
var https = require('https')
var net = require('net');
var fs = require('fs');
//var sockjs = require('sockjs');
require('events').EventEmitter.prototype._maxListeners = 100;
var ndws = require('nodejs-websocket');

var redis = require("redis");
var urlencode = require('urlencode');
var kanae = require('./lib/kanae');
var opt = {};
process.argv.forEach(function (val, index, array) {
    var d = val.split("=");
    if (d[0] === 'env') {
        if (d[1]){
            opt.env = d[1];
        }
    }else if(d[0] === 'sid'){
        if (d[1]){
            opt.sid = d[1];
        }
    }
});
console.log(opt);

var app = kanae.createApp(opt);
app.set('name', 'kanae');

// app configure
app.configure('production|stage|development', function() {
    
    app.loadConfig('mysqlConfig', app.getBase() + '/config/mysql.json');
    app.loadConfig('redisConfig', app.getBase() + '/config/redis.json');
    app.loadConfig('redisKeys', app.getBase() + '/config/redisKeys.json');
    app.loadConfig('sysConfig', app.getBase() + '/config/sysConfig.json');
});

// Configure database
app.configure('production|stage|development', 'chat|auth|connector|master', function() {

    var dbclient = require('./lib/dataFactory/mysql/mysql').init(app);
    
    app.set('dbclient', dbclient);
    
    var redisConfig = app.get('redisConfig');
    
    var redisClient = redis.createClient(redisConfig.port, redisConfig.host, redisConfig.options);
    redisClient.on("error", function(err) {
        console.log("Error " + err);
    });
    app.set('redisClient', redisClient);
});

var sysConfig = app.get('sysConfig');
var options = {};
/*
 * ssl 配置
 */
if(sysConfig.ssl === true){
    options = {
        secure: true,
        key: fs.readFileSync(app.getBase() + '/cert/server.key'),
        cert: fs.readFileSync(app.getBase() + '/cert/server.crt'),
        ca: fs.readFileSync(app.getBase() + '/cert/server-cert.pem'),
        requestCert: true,
        rejectUnauthorized: true
    };
}

var curServer = app.get('curServer');
var route= require('./lib/route');

var ws = ndws.createServer(options, function (conn) {
    conn.on('text', function (message) {
        message = urlencode.decode(message);
        console.log(message);
        if(!message || !(/^\{.*\}$/i.test(message))){
            var backData = {'route' : 'error', 'status' : false, 'error' : 'data parameter error.'};
            conn.send(JSON.stringify(backData));
            return ;
        }
        try {
            var msgObj = JSON.parse(message);
        }catch (e){
            var backData = {'route' : 'error', 'status' : false, 'error' : 'parameter is not a standard json data.'};
            conn.send(JSON.stringify(backData));
            return ;
        }
        
        if (typeof msgObj == 'object') {
            route.routeUtil(msgObj.route, msgObj.d, conn, function(error, data){
                var backData = {'route' : msgObj.route, 'status': false, 'error': '', 'd': null};
                if(error){
                     backData.error = error;
                }else{
                    backData.status = true;
                    backData.d = data;
                }
                console.log(backData);
                conn.send(JSON.stringify(backData));
            });
        } else {
            var backData = {'route' : 'error', 'status' : false, 'error' : 'parameter error.'};
            console.log(backData);
            conn.send(JSON.stringify(backData));
        }
    });
    
    conn.on('close', function (code, reason) {
        console.log('close conn code:' + code + ", reason:" + reason);
        route.destroyClient(conn, function(err, flg){
        });
    });
}).listen(curServer.port);

console.log('Server Client Listening on 0.0.0.0:' + curServer.port);


var serverPush = net.createServer();
serverPush.listen(curServer.clientPort, '0.0.0.0');
console.log('Server Push Listening on 0.0.0.0:' + curServer.clientPort );
serverPush.on('connection', function(conn) {
    console.log('connected: ' + conn.remoteAddress +':'+ conn.remotePort);
    conn.on('data', function (message) {
        message = urlencode.decode(message);
        console.log(message);
        if (!message || !(/^\{.*\}$/i.test(message))) {
            var backData = {'error' : 'data parameter error.'};
            conn.send(JSON.stringify(backData));
            return ;
        }
        var msgObj = JSON.parse(message);
        if (typeof msgObj == 'object') {
            route.pushRouteUtil(msgObj.route, msgObj.d, function(error, data){
                conn.destroy();
            });
        } else {
            conn.destroy();
        }
    });
    conn.on('close', function () {
        conn.destroy();
    });
});

//服务启动成功.
console.log('server is start.');

process.on('uncaughtException', function(err) {
    console.error(' Caught exception: ' + err.stack);
});