<?php

/**
 * GetCouchbaseRepository
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-16 17:05:16
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Services\Jpush;
use App\Models\Setting;
use App\Models\Users;
use JPush\Client;
use App\Libraries\BLogger;

class JpushService implements Jpush
{
    protected $settingModel = null;
    protected $setting = null;

    public function __construct(Setting $setting)
    {
        $this->settingModel = $setting;
        $this->setting = $this->settingModel->getSystemSetting(true);
    }
    
    /**
     * 生成一个消息推送构造器
     *
     * @param string $category   用户端 member| 服务端 service
     *
     * @return bool|\JPush\Client
     */
    protected function createjPush( $category = '' )
    {
        $app_key       = $this->setting['jpush_appkey'];
        $master_secret = $this->setting['jpush_master_secret'];
        if ( empty( $app_key ) || empty( $master_secret ) )
        {
            return false;
        }
        //
        $log_path = storage_path('logs') . DIRECTORY_SEPARATOR . 'push-' . date( 'Y-m-d' ) . '.log';
        //init
        $client = new \JPush\Client( $app_key, $master_secret, $log_path);
        
        return $client;
    }
    
    /**
     * 给指定用户发送消息或通知
     *
     * @param        $device_tokens
     * @param        $message
     * @param string $type
     * @param null   $title
     * @param null   $content_type
     * @param null   $extras
     *
     * @return bool
     */
    public function pushMemberMessage($device_tokens, $message, $type = 'alert', $extras = null, $title = null, $content_type = null)
    {
        try
        {
            $client = $this->createjPush();
            $pusher = $client->push();
            $pusher->options( ['apns_production' => true] );
            $pusher->setPlatform( 'all' );
            #设定push RegistrationIds 可设置多个device_token
            foreach ($device_tokens as $device_token) {
                $pusher->addRegistrationId( strval( $device_token ) );
            }
            if ( $type == 'alert' ) {
                $pusher->iosNotification($message, [
                    'extras' => empty($extras) ? [] : $extras,
                ])
                ->androidNotification($message, [
                    'title' => $title,
                    'extras' => empty($extras) ? [] : $extras,
                ]);
            } else {
                $pusher->setMessage($message, $title, $content_type, $extras);
            }
            $res = $pusher->send();
        }
        catch ( \JPush\Exceptions\APIConnectionException $e )
        {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->__toString());
            return false;
        }
        catch ( \JPush\Exceptions\APIRequestException $e )
        {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->__toString());
            return false;
        }
        catch ( \JPush\Exceptions\ServiceNotAvaliable $e )
        {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->__toString());
            return false;
        }
        return true;
    }
    
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
    public function pushAllMemberMessage($message, $type = 'alert', $title = null, $content_type = null, $extras = null )
    {
        try
        {
            $client = $this->createjPush();
            $pusher = $client->push();
            $pusher->options( ['apns_production' => true] );
            $pusher->setPlatform( 'all' );
            $pusher->addAllAudience();
            if ( $type == 'alert' )
            {
                $pusher->iosNotification($message, [
                    'extras' => empty($extras) ? [] : $extras,
                ])
                ->androidNotification($message, [
                    'title' => $title,
                    'extras' => empty($extras) ? [] : $extras,
                ]);
            } else
            {
                $pusher->setMessage( $message, $title, $content_type, $extras );
            }
            $res = $pusher->send();
        }
        catch ( \JPush\Exceptions\APIConnectionException $e )
        {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->__toString());
            return false;
        }
        catch ( \JPush\Exceptions\APIRequestException $e )
        {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->__toString());
            return false;
        }
        catch ( \JPush\Exceptions\ServiceNotAvaliable $e )
        {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->__toString());
            return false;
        }
        
        return true;
    }
    
}
