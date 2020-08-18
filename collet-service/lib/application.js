var fs = require('fs');
var path = require('path');
var logger = require('./logger/logger').getLogger('ordering');
var Constants = require('./static/constants');
var session = require('./utils/session');

var STATE_INITED = 1;  // app has inited
var STATE_START = 2;  // app start
var STATE_STARTED = 3;  // app has started
var STATE_STOPED = 4;  // app has stoped

var Application = module.exports = {};

Application.init = function (opts) {
    opts = opts || {};
    var base = opts.base || path.dirname(require.main.filename);
    var env = opts.env || 'production';
    var sid = opts.sid || '';
    this.settings = {};     // collection keep set/get
    this.clients = {};  //save clients, user_id => conn
    this.session = session.init();
    var self = this;
    this.set(Constants.RESERVED.ENV, env, true);
    this.set(Constants.RESERVED.BASE, base, true);
    
    
    // current server info
    this.serverId = null;   // current server id
    this.curServer = null;  // current server info
    this.startTime = null; // current server start time
    this.servers = [];          // current global server info maps
    this.serverIdMaps = {};   // current global server id maps, id -> [info]
    this.state = STATE_INITED;
    
    this.loadConfig('servers', base + '/config/servers.json');
    this.servers = this.get('servers');
    
    var sid_exist = false;
    this.servers.forEach(function(val, k){
        if(sid_exist === false && sid === val.id){
            sid_exist = true;
        }
        self.serverIdMaps[val.id] = val;
    });
    
    if(sid_exist === false){
        sid = this.servers[0].id;
    }
    
    self.set(Constants.RESERVED.SERVER_ID, sid, true);
    self.set(Constants.RESERVED.CURRENT_SERVER, self.serverIdMaps[this.serverId], true);
    
    logger.info('application curServer: %j', this.curServer);
    
    logger.info('application inited: %j', this.getServerId());
};

/**
 * Get application base path
 *
 *  // cwd: /home/game/
 *  pomelo start
 *  // app.getBase() -> /home/game
 *
 * @return {String} application base path
 *
 * @memberOf Application
 */
Application.getBase = function () {
    return this.get(Constants.RESERVED.BASE);
};


/**
 * Assign `setting` to `val`, or return `setting`'s value.
 *
 * Example:
 *
 *  app.set('key1', 'value1');
 *  app.get('key1');  // 'value1'
 *  app.key1;         // undefined
 *
 *  app.set('key2', 'value2', true);
 *  app.get('key2');  // 'value2'
 *  app.key2;         // 'value2'
 *
 * @param {String} setting the setting of application
 * @param {String} val the setting's value
 * @param {Boolean} attach whether attach the settings to application
 * @return {Server|Mixed} for chaining, or the setting value
 * @memberOf Application
 */
Application.set = function (setting, val, attach) {
    if (arguments.length === 1) {
        return this.settings[setting];
    }
    this.settings[setting] = val;
    if (attach) {
        this[setting] = val;
    }
    return this;
};

/**
 * Get property from setting
 *
 * @param {String} setting application setting
 * @return {String} val
 * @memberOf Application
 */
Application.get = function (setting) {
    return this.settings[setting];
};

/**
 * Load Configure json file to settings.
 *
 * @param {String} key environment key
 * @param {String} val environment value
 * @return {Server|Mixed} for chaining, or the setting value
 * @memberOf Application
 */
Application.loadConfig = function (key, val) {
    var env = this.get(Constants.RESERVED.ENV);
    val = require(val);
    if (val[env]) {
        val = val[env];
    }
    this.set(key, val);
};

var contains = function (str, settings) {
    if (!settings) {
        return false;
    }

    var ts = settings.split("|");
    for (var i = 0, l = ts.length; i < l; i++) {
        if (str === ts[i]) {
            return true;
        }
    }
    return false;
};

/**
 * Configure callback for the specified env and server type.
 * When no env is specified that callback will
 * be invoked for all environments and when no type is specified
 * that callback will be invoked for all server types.
 *
 * Examples:
 *
 *  app.configure(function(){
 *    // executed for all envs and server types
 *  });
 *
 *  app.configure('development', function(){
 *    // executed development env
 *  });
 *
 *  app.configure('development', 'connector', function(){
 *    // executed for development env and connector server type
 *  });
 *
 * @param {String} env application environment
 * @param {Function} fn callback function
 * @param {String} type server type
 * @return {Application} for chaining
 * @memberOf Application
 */
Application.configure = function (env, fn) {
    var args = [].slice.call(arguments);
    fn = args.pop();
    env = Constants.RESERVED.ALL;

    if (args.length > 0) {
        env = args[0];
    }

    if (env === Constants.RESERVED.ALL || contains(this.settings.env, env)) {
        fn.call(this);
    }
    return this;
};

/**
 * Get current server info.
 *
 * @return {Object} current server info, {id, serverType, host, port}
 * @memberOf Application
 */
Application.getCurServer = function () {
    return this.curServer;
};

/**
 * Get current server id.
 *
 * @return {String|Number} current server id from servers.json
 * @memberOf Application
 */
Application.getServerId = function () {
    return this.serverId;
};

Application.addSession = function (user_id, conn, desk_id) {
    if(user_id === undefined || conn === undefined || desk_id === undefined){
        logger.info('addSession is parma error');
        return false;
    }
    this.session.addSession(user_id, conn, desk_id);
    return this;
};

Application.getSession = function (session_id) {
    return this.session.getSession(session_id);
};

Application.delSession = function (session_id) {
    this.session.delSession(session_id);
};

Application.getSessionId = function (user_id) {
    return this.session.getSessionId(user_id);
};
Application.delSessionId = function (user_id) {
    this.session.delSessionId(user_id);
};

Application.getServerById = function (serverId) {
    if(!serverId || !this.serverIdMaps[serverId]){
        return false;
    }
    return this.serverIdMaps[serverId];
};







