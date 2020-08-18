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

interface Alipay
{
    /**
     * 获取app支付支付字符串
     * @param type $order_no
     * @param type $order_amount
     * @param type $notify_url
     * @param type $subject
     */
    public function getAlipayStr($order_no, $order_amount, $notify_url, $subject);
    
    /**
     * 获取小程序支付的trade no
     * 
     * @param type $order_no
     * @param type $order_amount
     * @param type $notify_url
     * @param type $subject
     * @param type $openid
     * @param type $trade_type
     */
    public function getAlipayTradeCreate($order_no, $order_amount, $notify_url, $subject, $openid, $trade_type);
    
    /**
     * 订单退款接口
     * @param type $out_trade_no  //本地支付流水号
     * @param type $order_amount 退款金额
     * @return boolean
     */
    public function tradeRefund($out_trade_no, $order_amount);
    
    /**
     * 
     * @param type $data
     */
    public function rsaCheckV1($data);
    
    
    public function getQrCode($order_no, $order_amount, $notify_url, $subject);
    
    public function tradeStatusQuery($order_no);
    
    public function tradeCancel($order_no);
    
    public function getAuthTokenByCode($code, $type);
    
    public function createAlipayQrcode ($path, $query, $describe);
    
}
