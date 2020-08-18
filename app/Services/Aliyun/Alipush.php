<?php

/**
 * Ocr
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 14:36:54
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Services\Aliyun;

interface Alipush
{
    /**
     * 给指定用户发送消息或通知
     * 
     * @param type $device_tokens
     * @param type $message
     * @param type $type
     * @param type $extras
     * @param type $title
     * @param type $device_type
     * @param type $target
     */
    public function pushMemberMessage($device_tokens, $message, $type = 'alert', $extras = null, $title = null, $device_type = 'android', $target = 'ALL');
    
    /**
     * 给所有用户发送消息或通知
     * 
     * @param type $message
     * @param type $type
     * @param type $extras
     * @param type $title
     * @param type $device_type
     */
    public function pushAllMemberMessage($message, $type = 'alert', $extras = null, $title = null, $device_type = 'android');
}
