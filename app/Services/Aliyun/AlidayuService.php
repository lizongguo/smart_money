<?php
namespace App\Services\Aliyun;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Libraries\BLogger;

/**
 * Class 阿里大于 Sms
 * 
 * 工程编码采用UTF-8
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 18:07:21
 * @copyright   Copyright(C) bravesoft Inc.
 */

class AlidayuService implements Alidayu
{
    protected $clientName = \ALIBABA_CLOUD_GLOBAL_CLIENT;
    protected $acsClient = null;
    protected $setting = null;
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $signName;
    
    protected $query;

    public function __construct() {
        $settingModel = app()->make('App\Models\Setting'); 
        $this->setting = $settingModel->getShopSettingByCategory(0, '短信配置');
        
        $this->accessKeyId = $this->setting['sms_accessKeyId'];
        $this->accessKeySecret = $this->setting['sms_accessKeySecret'];
        $this->signName = $this->setting['sms_signName'];
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
     * 发送短信
     * @param string $phone
     * @param array $param
     * @param string $templateCode
     * @param string $signName
     * @param string $outId
     * @param string $extendCode
     * @return stdClass
     */
    public function sendSms($phone, $param, $templateCode, $signName = null, $outId = null, $extendCode = null)
    {
        $this->getAcsClient();
        $this->query = [
            'RegionId' => 'cn-hangzhou',
            'PhoneNumbers' => $phone,
            'SignName' => empty($signName) ? $this->signName :  $signName,
            'TemplateCode' => $templateCode,
            'TemplateParam' => json_encode($param)
        ];
        if (!empty($outId)) {
            $this->query['OutId'] = $outId;
        }
        if (!empty($extendCode)) {
            $this->query['SmsUpExtendCode'] = $extendCode;
        }
        try {
            
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                 ->scheme('https')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => $this->query,
                ])
                ->request();
            if ($result->Code !== 'OK') {
                return false;
            }
        } catch (ClientException $e) {
            BLogger::getLogger(BLogger::LOG_SMS)->error($e->getErrorMessage());
            return FALSE;
        } catch (ServerException $e) {
            BLogger::getLogger(BLogger::LOG_SMS)->error($e->getErrorMessage());
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 批量发送短信
     * 
     * @param array $phones
     * @param array $params
     * @param string $templateCode
     * @param array $signNames
     * @return stdClass
     */
    public function sendBatchSms($phones, $params, $templateCode, $signNames)
    {
        $this->getAcsClient();
        $this->query = [
            'PhoneNumberJson' => json_encode($phones, JSON_UNESCAPED_UNICODE),
            'SignNameJson' => json_encode($signNames, JSON_UNESCAPED_UNICODE),
            'TemplateCode' => $templateCode,
            'TemplateParamJson' => json_encode($param),
            'RegionId' => 'cn-hangzhou',
        ];
        
        try {
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                 ->scheme('https')
                ->version('2017-05-25')
                ->action('SendBatchSms')
                ->method('POST')
                ->options([
                    'query' => $this->query,
                ])
                ->request();
            if ($result->Code !== 'OK') {
                return false;
            }
        } catch (ClientException $e) {
            BLogger::getLogger(BLogger::LOG_SMS)->error($e->getErrorMessage());
            return FALSE;
        } catch (ServerException $e) {
            BLogger::getLogger(BLogger::LOG_SMS)->error($e->getErrorMessage());
            return FALSE;
        }
        return TRUE;
    }
}
