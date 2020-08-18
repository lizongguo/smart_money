<?php

namespace App\Libraries;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Log\Logger as Writer;

/**
 * 自定义log文件的全局log类
 */
class BLogger {
    
    // 所有的LOG都要求在这里注册
    const LOG_SYSTEM = 'laravel';
    const LOG_ERROR = 'error'; #错误日志
    const LOG_DB_QUERY = 'query'; #sql日志
    const LOG_API_RECORD = 'api'; #api请求日志
    const LOG_WX_PAY = 'wxpay'; #微信支付日志
    const LOG_WX_API = 'wxapi'; #微信api接口相关
    const LOG_WX_CALLBACK = 'wxcallback'; #微信api接口相关
    const LOG_ORDER_CALLBACK = 'callback'; #支付回调日志
    const LOG_PUSH = 'push'; #push 日志
    const LOG_SCHEDULE = 'schedule'; #schedule 日志
    const LOG_SMS = 'sms'; #短信发送日志
    const LOG_REFUND = 'refundpay'; #退款日志
    CONST LOG_PEISONG = 'peisong'; #外卖配送日志
    const LOG_ALIPAY = 'alipay'; #支付宝支付相关的返回日志

    private static $loggers = array();

    /**
     * 获取日志类
     * @param type $type 日志存储文件名
     * @return type
     */
    public static function getLogger($type = self::LOG_SYSTEM) {
        if (empty(self::$loggers[$type])) { 
            self::$loggers[$type] = new Writer(new Logger($type));
            self::$loggers[$type]->pushHandler(
                new StreamHandler(storage_path() . '/logs/' . $type . '-'.date('Y-m-d').'.log', config('app.log_level', 'debug'))
            );
        }
        $log = self::$loggers[$type];
        return $log;
    }

}
