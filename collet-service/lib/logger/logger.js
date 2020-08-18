var path = require("path");
var log4js = require("log4js");
/**
 * 日志配置
 */
function configure() {
     
}

/**
 * 暴露到应用的日志接口，调用该方法前必须确保已经configure过
 * @param name 指定log4js配置文件中的category。依此找到对应的appender。
 *              如果appender没有写上category，则为默认的category。可以有多个
 * @returns {Logger}
 */
function getLogger (name) {
    
    log4js.configure("./config/log4js.json");
    var dateFileLog = log4js.getLogger(name);
    dateFileLog.setLevel(log4js.levels.INFO);
    return dateFileLog;
}

module.exports ={
	getLogger: getLogger,
	getDefaultLogger: log4js.getDefaultLogger,
	addAppender: log4js.addAppender,
	loadAppender: log4js.loadAppender,
	clearAppenders: log4js.clearAppenders,
	configure: configure,
	replaceConsole: log4js.replaceConsole,
	restoreConsole: log4js.restoreConsole,

	levels: log4js.levels,
	setGlobalLogLevel: log4js.setGlobalLogLevel,

	layouts: log4js.layouts,
	appenders: log4js.appenders
};