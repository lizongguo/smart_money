<?php

/**
 * Jpush 
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-16 17:05:16
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Services\Jpush;

interface Jpush
{
    
    /**
     * 给指定用户发送消息或通知
     *
     * 
     * @param type $device_tokens
     * @param type $message
     * @param type $type
     * @param type $extras
     * @param type $title
     * @param type $content_type
     */
    public function pushMemberMessage($device_tokens, $message, $type = 'alert', $extras = null, $title = null, $content_type = null);
    
    /**
     * 给所有用户发送消息或通知
     *
     * @param        $message
     * @param string $type
     * @param null   $title
     * @param null   $content_type
     * @param null   $extras
     *
     * @return bool
     */
    public function pushAllMemberMessage($message, $type = 'alert', $title = null, $content_type = null, $extras = null );
}
