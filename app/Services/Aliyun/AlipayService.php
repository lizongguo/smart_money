<?php
namespace App\Services\Aliyun;
use App\Models\Setting;
use App\Libraries\BLogger;

require_once __DIR__ . '/Alipay/AopSdk.php';

/**
 * Class 阿里Pay
 * 
 * 工程编码采用UTF-8
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-8-3 18:07:21
 * @copyright   Copyright(C) kbftech Inc.
 */

class AlipayService implements Alipay
{
    protected $aopClient = null;
    protected $config = null;
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $signName;

    public function __construct() {
        $setting = app()->make('App\Models\Setting');
        $this->config = $setting->getShopSettingByCategory(0, '支付宝小程序');
    }
    
    /**
     * 取得Client
     * @return getAopClient
     */
    protected function getAopClient() {
        if($this->aopClient == null) {
            $aop = new \AopClient;
            $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
            $aop->appId = $this->config['ali_app_id'];
            $aop->rsaPrivateKey = $this->config['ali_rsaPrivateKey'];
//            $aop->rsaPrivateKeyFilePath = $this->config['rsaPrivateKeyFilePath'];
            $aop->format = "json";
            $aop->charset = "UTF-8";
            $aop->signType = "RSA2";
            $aop->alipayrsaPublicKey = $this->config['ali_alipayrsaPublicKey'];
            $this->aopClient = $aop;
        }
        return $this->aopClient;
    }
    
    /**
     * 获取app支付的支付码
     * @param string $order_no
     * @param string $order_amount
     * @param string $notify_url
     * @param type $subject
     */
    public function getAlipayStr($order_no, $order_amount, $notify_url, $subject)
    {
        if(empty($order_no) || !is_numeric($order_amount) || empty($notify_url)|| empty($subject)) {
            return false;
        }
        $request = new \AlipayTradeAppPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $goodsData = [
            'total_amount' => $order_amount,
            'out_trade_no' => $order_no,
            'subject' => $subject,
            'body' => '在线点餐 ' . $subject,
            'timeout_express' => '7d',
            'product_code' => 'QUICK_MSECURITY_PAY',
        ];
        
        $bizcontent = json_encode($goodsData);
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($bizcontent);
        
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $this->getAopClient()->sdkExecute($request);
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info((array)$response);
        return $response;//就是orderString 可以直接给客户端请求，无需再做处理。
    }
    
    /**
     * 获取app支付的支付码
     * @param string $order_no
     * @param string $order_amount
     * @param string $notify_url
     * @param type $subject
     * @param type $openid 用户授权的user_id
     * @param type $extend 无用参数
     */
    public function getAlipayTradeCreate($order_no, $order_amount, $notify_url, $subject, $openid, $extend = null)
    {
        if(empty($order_no) || !is_numeric($order_amount) || empty($notify_url)|| empty($subject) || empty($openid)) {
            return false;
        }
        $request = new \AlipayTradeCreateRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $goodsData = [
            'total_amount' => $order_amount,
            'out_trade_no' => $order_no,
            'subject' => $subject,
            'body' => '在线点餐 ' . $subject,
            'timeout_express' => '7d',
            'buyer_id' => $openid,
        ];
        
        $bizcontent = json_encode($goodsData);
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($bizcontent);
        
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $this->getAopClient()->execute($request);
        
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $data = $response->$responseNode;
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info([$responseNode, (array)$result->$responseNode]);
        if(!empty($data->code) && $data->code == 10000){
            return ['trade_no' => $data->trade_no];
        } else {
            return false;
        }
    }
    
    /**
     * 订单退款接口
     * @param type $out_trade_no  //本地支付流水号
     * @param type $order_amount 退款金额
     * @return boolean
     */
    public function tradeRefund($out_trade_no, $order_amount)
    {
        if(empty($out_trade_no) || !is_numeric($order_amount)) {
            return false;
        }
        $request = new \AlipayTradeRefundRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $goodsData = [
            'total_amount' => $order_amount,
            'out_trade_no' => $out_trade_no,
            'refund_reason' => "【{$out_trade_no}】订单退款。"
        ];
        
        $bizcontent = json_encode($goodsData);
        $request->setBizContent($bizcontent);
        
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $this->getAopClient()->execute($request);
        
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $data = $response->$responseNode;
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info([$responseNode, (array)$result->$responseNode]);
        if(!empty($data->code) && $data->code == 10000){
            return true;
        } else {
            return false;
        }
    } 
    
    
    /**
     *  回调签名验证
     * @param type $data
     * @return type
     */
    public function rsaCheckV1($data)
    {
        return $this->getAopClient()->rsaCheckV1($data, NULL, "RSA2");
    }
    
    /**
     * 生成二维码支付功能
     * @param type $order_no
     * @param type $order_amount
     * @param type $notify_url
     * @param type $subject
     */
    public function getQrCode($order_no, $order_amount, $notify_url, $subject)
    {
        if(empty($order_no) || !is_numeric($order_amount) || empty($notify_url)|| empty($subject)) {
            return false;
        }
        $request = new \AlipayTradePrecreateRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $goodsData = [
            'total_amount' => $order_amount,
            'out_trade_no' => $order_no,
            'subject' => $subject,
            'body' => '云乐享车 ' . $subject,
            'timeout_express' => '2h',
            'qr_code_timeout_express' => '2h'
        ];
        
        $bizcontent = json_encode($goodsData);
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($bizcontent);
        
        $result = $this->getAopClient()->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info([$responseNode, (array)$result->$responseNode]);
        if(!empty($resultCode) && $resultCode == 10000){
            return $result->$responseNode->qr_code;
        } else {
            return false;
        }
    }
    
    /**
     * 获取订单支付状态
     * @param type $order_no
     */
    public function tradeStatusQuery($order_no)
    {
        if(empty($order_no)) {
            return false;
        }
        $request = new \AlipayTradeQueryRequest ();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $goodsData = [
            'out_trade_no' => $order_no
        ];
        $bizcontent = json_encode($goodsData);
        $request->setBizContent($bizcontent);
        
        $result = $this->getAopClient()->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $data = $result->$responseNode;
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info([$responseNode, (array)$data]);
        if(!empty($data->code) && $data->code == 10000){
            return $data->trade_status;
        } else {
            return false;
        }
    }
    
    /**
     * 关闭订单
     * @param type $order_no
     * @return boolean
     */
    public function tradeCancel($order_no)
    {
        if(empty($order_no)) {
            return false;
        }
        $request = new \AlipayTradeCancelRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $goodsData = [
            'out_trade_no' => $order_no
        ];
        $bizcontent = json_encode($goodsData);
        $request->setBizContent($bizcontent);
        
        $result = $this->getAopClient()->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $data = $result->$responseNode;
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info([$responseNode, (array)$data]);
        if(!empty($data->code) && $data->code == 10000){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 通过授权code 获取用户 auth token
     * 
     * @param type $code
     * @param type $type authorization_code | refresh_token
     * @return boolean
     */
    public function getAuthTokenByCode($code, $type = 'authorization_code')
    {
        if(empty($code)) {
            return false;
        }
        $request = new \AlipaySystemOauthTokenRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $request->setGrantType($type);
        if ($type == 'authorization_code') {
            $request->setCode($code);
        } else {
            $request->setRefreshToken($code);
        }
        
        $result = $this->getAopClient()->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $data = $result->$responseNode;
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info([$responseNode, (array)$data]);
        if(!empty($data) && !empty($data->user_id)){
            return (array)$data;
        } else {
            return false;
        }
    }
    
    /**
     * 生成 支付宝小程序二维码
     * @param type $path
     * @param array $query
     * @param type $describe
     */
    public function createAlipayQrcode ($path, $query, $describe = '二维码描述')
    {
        if(empty($path) || empty($query)) {
            return false;
        }
        $request = new \AlipayOpenAppQrcodeCreateRequest ();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $goodsData = [
            'url_param' => $path,
            'query_param' => http_build_query($query),
            'describe' => $describe,
        ];
        $bizcontent = json_encode($goodsData);
        $request->setBizContent($bizcontent);
        
        $result = $this->getAopClient()->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $data = $result->$responseNode;
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info($responseNode);
        BLogger::getLogger(BLogger::LOG_ALIPAY)->info((array)$data);
        if(!empty($data->code) && $data->code == 10000){
            return $data->qr_code_url;
        } else {
            return false;
        }
    }
}
