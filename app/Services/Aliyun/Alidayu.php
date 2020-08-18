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

interface Alidayu
{
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
    public function sendSms($phone, $param, $templateCode, $signName, $outId, $extendCode);
    
    /**
     * 批量发送短信
     * 
     * @param array $phones
     * @param array $params
     * @param string $templateCode
     * @param array $signNames
     * @return stdClass
     */
    public function sendBatchSms($phones, $params, $templateCode, $signNames);
}
