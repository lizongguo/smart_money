<?php
namespace App\Services\Baidu;

use App\Services\Baidu\sms\BaiduSmsClient;

/**
 * Class 百度短信 Sms
 * 
 * 工程编码采用UTF-8
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 18:07:21
 * @copyright   Copyright(C) bravesoft Inc.
 */

class BaidusmsService
{
    protected $acsClient = null;
    protected $setting = null;
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $signName;

    public function __construct() {
        // 加载区域结点配置
        $this->setting = config('baidu.sms');
        $this->accessKeyId = $this->setting['accessKeyId'];
        $this->accessKeySecret = $this->setting['accessKeySecret'];
        $this->signName = $this->setting['templateSign'];
    }
    
    /**
     * 取得AcsClient
     *
     * @return BaiduSmsClient
     */
    protected function getAcsClient() {
        $endPoint = "sms.bj.baidubce.com";
        
        if($this->acsClient == null) {
            // 初始化AcsClient用于发起请求
            $this->acsClient = new BaiduSmsClient($this->accessKeyId, $this->accessKeySecret, $endPoint);;
        }
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
    public function sendSms(string $phone, array $param, string $templateCode, string $signName = null, string $outId = null, string $extendCode = null)
    {
        $request = [
            'invokeId' => empty($signName) ? $this->signName : $signName,
            'phoneNumber' => $phone,
            'templateCode' => $templateCode,
            'contentVar' => $param,
        ];
        
        // 发起访问请求
        $acsResponse = $this->getAcsClient()->sendMessage($request);
        
        if (!!$acsResponse && $acsResponse->code == 1000) {
            return true;
        }else{
            return false;
        }
    }

}
