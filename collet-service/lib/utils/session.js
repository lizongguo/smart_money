var Buffer = require('buffer').Buffer;
var Crypto = require("crypto");

var session = module.exports;
var chatPre = "chat_";

session.init = function () {
    this.sessions = {}; // current global session maps, session_id -> [user_id, conn]
    this.userIdMaps = {};   // current global userId maps, user_id -> [session_id]
    console.log('session is start work.');
    return this;
};

session.md5  = function ( data ) {
    var buf = new Buffer(data);
    var str = buf.toString("binary");
    return Crypto.createHash("md5").update(str).digest("hex");
};

session.addSession = function (user_id, client, desk_id) {
    var session_id = this.md5("session"+(user_id + (new Date().getTime())));
    this.sessions[session_id] = {'user_id': user_id, 'client': client, 'desk_id':desk_id};
//    if(is_chat === 1) {
//        this.userIdMaps[chatPre + user_id] = session_id;
//    }else{
        this.userIdMaps[user_id] = session_id;
//    }
    console.log('binding user is success: user_id:' + user_id + ",session_id:" + session_id + ",desk_id:" + desk_id);
    return this;
};

session.getSession = function (session_id) {
    if(!!this.sessions[session_id]){
        return this.sessions[session_id];
    }
    return false;
};

session.getSessionId = function (user_id) {
    var key = user_id;
    
    if(!!this.userIdMaps[key]){
        return this.userIdMaps[key];
    }
    return false;
};

session.delSession = function (session_id) {
    console.info(this.sessions[session_id]);
    if(!!this.sessions[session_id]){
        delete this.sessions[session_id]['user_id'];
        delete this.sessions[session_id]['uuid'];
        delete this.sessions[session_id]['desk_id'];
        delete this.sessions[session_id]['client']['name'];
        this.sessions[session_id]['client'].close();
        delete this.sessions[session_id];
    }
};

session.delSessionId = function (user_id) {
    var key = user_id;
    if(!!this.userIdMaps[key]){
        delete this.userIdMaps[key];
    }
};









