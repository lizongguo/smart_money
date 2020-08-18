<?php
namespace App\Services\Aliyun;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Libraries\BLogger;

/**
 * Class 阿里推送
 * 
 * 工程编码采用UTF-8
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 18:07:21
 * @copyright   Copyright(C) bravesoft Inc.
 */

class AlipushService implements Alipush
{
    protected $clientName = \ALIBABA_CLOUD_GLOBAL_CLIENT;
    protected $acsClient = null;
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $androidAppKey;
    protected $iosAppKey;
    protected $query;
    
    public function __construct() {
        $settingModel = app()->make('App\Models\Setting'); 
        $this->setting = $settingModel->getShopSettingByCategory(0, '推送');
        $this->accessKeyId = $this->setting['push_accessKeyId'];
        $this->accessKeySecret = $this->setting['push_accessKeySecret'];
        $this->androidAppKey = $this->setting['push_androidAppKey'];
        $this->iosAppKey = $this->setting['push_iosAppKey'];
    }
    
    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    protected function getAcsClient() {
        // 暂时不支持多Region
        $region = "cn-hangzhou";
        if (!AlibabaCloud::has($this->clientName)) {
            AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
                ->regionId($region) // replace regionId as you need
                ->name($this->clientName);
        }
        $this->acsClient = AlibabaCloud::get($this->clientName);
        
        return $this->acsClient;
    }

    /**
     * 给指定用户发送消息或通知
     *
     * @param type $device_tokens
     * @param type $message
     * @param type $type
     * @param type $extras
     * @param type $title
     * @param type $device_type ios android
     * @param type $target
     * @return boolean
     */
    public function pushMemberMessage($device_tokens, $message, $type = 'alert', $extras = null, $title = null, $device_type = 'android', $target = 'DEVICE')
    {
        //关闭推送直接返回
        if ($this->setting['push_state'] != 1) {
            return true;
        }
        $appKey = ($device_type == 'ios') ? $this->iosAppKey : $this->androidAppKey;
        $pushTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 second'));
        $expireTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+2 hours'));
        if (empty($appKey)) { //未设置推送key，直接返回
            return true;
        }
        
        $this->getAcsClient();
        try {
            $this->query = [
                'AppKey' => $appKey,
                'PushType' => $type != 'alert' ? 'MESSAGE' : 'NOTICE',
                'DeviceType' => 'ALL',
                'Target' => in_array($target, ['DEVICE', 'ACCOUNT', 'TAG', 'ALL']) ? $target : 'DEVICE',
                'TargetValue' => implode(',', $device_tokens),
                'Title' => $title,
                'Body' => $message,
                'PushTime' => $pushTime,
                'ExpireTime' => $expireTime,
            ];
            //设置区域数据
            $this->setDeviceData($device_type, $title, $extras);
            
            $result = AlibabaCloud::rpcRequest()
                ->product('Push')
                 ->scheme('https')
                ->version('2016-08-01')
                ->action('Push')
                ->method('POST')
                ->options([
                    'query' => $this->query,
                ])
                ->request();
            
            if (!isset($result->MessageId)) {
                return false;
            }
        } catch (ClientException $e) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->getErrorMessage());
            return FALSE;
        } catch (ServerException $e) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->getErrorMessage());
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * 给所有用户发送消息或通知
     * 
     * @param type $message
     * @param type $type
     * @param type $extras
     * @param type $title
     * @param type $device_type ios android
     * @return boolean
     */
    public function pushAllMemberMessage($message, $type = 'alert', $extras = null, $title = null, $device_type = 'android')
    {
        //关闭推送直接返回
        if ($this->setting['push_state'] != 1) {
            return true;
        }
        $appKey = ($device_type == 'ios') ? $this->iosAppKey : $this->androidAppKey;
        $pushTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 second'));
        $expireTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+2 hours'));
        if (empty($appKey)) { //未设置推送key，直接返回
            return true;
        }
        
        $this->getAcsClient();
        try {
            $this->query = [
                'AppKey' => $appKey,
                'PushType' => $type != 'alert' ? 'MESSAGE' : 'NOTICE',
                'DeviceType' => 'ALL',
                'Target' => 'ALL',
                'TargetValue' => 'ALL',
                'Title' => $title,
                'Body' => $message,
                'PushTime' => $pushTime,
                'ExpireTime' => $expireTime,
            ];
            
            //设置区域数据
            $this->setDeviceData($device_type, $title, $extras);
            
            $result = AlibabaCloud::rpcRequest()
                ->product('Push')
                 ->scheme('https')
                ->version('2016-08-01')
                ->action('Push')
                ->method('POST')
                ->options([
                    'query' => $this->query,
                ])
                ->request();
            
            if (!isset($result->MessageId)) {
                return false;
            }
        } catch (ClientException $e) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->getErrorMessage());
            return FALSE;
        } catch (ServerException $e) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($e->getErrorMessage());
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * 
     * @param type $device_type
     * @param type $title
     * @param type $extras
     */
    protected function setDeviceData($device_type, $title= null, $extras= null)
    {
        if ($device_type == 'ios')
        {
            $this->query['iOSApnsEnv'] = config('app.env') == 'production' ? 'PRODUCT' : 'DEV';
            $this->query['iOSMusic'] = 'default';
            $this->query['iOSRemind'] = 'true';
            $this->query['iOSRemindBody'] = $title;
            $extKey = 'iOSExtParameters';
            
        } else {
            // 推送配置: Android
            $this->query['AndroidNotifyType'] = 'SOUND';
            $this->query['AndroidMusic'] = 'default';
            $this->query['AndroidNotificationChannel'] = '1';
            $extKey = 'AndroidExtParameters';
//            $this->query['AndroidOpenType'] = 'URL';
//            $this->query['AndroidOpenUrl'] = 'http://www.aliyun.com';
//            $this->query['AndroidActivity'] = 'com.ali.demo.OpenActivity';
        }
        
        if (!empty($extras) && count($extras) > 0) {
            //自定义的kv结构,开发者扩展用 针对iOS设备
            $this->query[$extKey] = json_encode($extras);
        }
    }
}
