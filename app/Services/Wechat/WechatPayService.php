<?php
namespace App\Services\Wechat;

use App\Models\Setting;
use App\Libraries\BLogger;
/**
 * @class WechatPay
 * @brief 移动微信支付
 * @date 2018/9/25 15:45:40
 */
class WechatPayService
{
    //支付插件名称
    protected $name = '移动微信支付';
    protected $config = null;
    protected $settingModel = null;
    protected $setting = null;
    /**
     * 交易类型
     */
    const JSAPI = 'JSAPI';
    const NATIVE = 'NATIVE';
    const APP = 'APP';
    const MWEB = 'MWEB';
    const MICROPAY = 'MICROPAY';

    public function __construct() {
        $setting = app()->make('App\Models\Setting');
        $this->config = $setting->getShopSettingByCategory(0, '微信小程序');
        $this->config['type'] = 1; //公众号 小程序 支付
    }

    public function setConfig($application) {
        $this->config = $application;
    }
    
    /**
     * @see paymentplugin::getSubmitUrl()
     */
    private function getSubmitUrl()
    {
        return 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    }
    
    /**
     * 获取查询订单api接口
     * @return string
     */
    private function getOrderQuery() {
        return 'https://api.mch.weixin.qq.com/pay/orderquery';
    }
    
    /**
     * 关闭订单api
     * @return string
     */
    private function getCloseOrder(){
        return 'https://api.mch.weixin.qq.com/pay/closeorder';
    }
    
    /**
     * 订单退款
     * @return string
     */
    private function getPayRefund(){
        return 'https://api.mch.weixin.qq.com/secapi/pay/refund';
    }
    

    public function notifyStop()
    {
        die("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
    }

    /**
     * @brief 提交数据
     * @param xml $xmlData 
     * 
     * @param \App\Services\Wechat\xml $xmlData 要发送的xml数据
     * @param type $type 请求的类型
     * @param type $useCert 时否 证书验证
     * @return xmlStr
     */
    private function curlSubmit($xmlData, $type = 'getSubmitUrl', $useCert = false)
    {
        //接收xml数据的文件
        $url = $this->$type();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if($useCert == true){
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			//证书文件请放入服务器的非web目录下
			$sslCertPath = storage_path('wxApiPayCert') . DIRECTORY_SEPARATOR . 'apiclient_cert.pem';
			$sslKeyPath = storage_path('wxApiPayCert') . DIRECTORY_SEPARATOR . 'apiclient_key.pem';;
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, $sslCertPath);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, $sslKeyPath);
		}

        $response = curl_exec($ch);
        curl_close($ch);
        BLogger::getLogger(BLogger::LOG_WX_PAY)->info($response);
        return $response;
    }

    /**
     * @brief 从array到xml转换数据格式
     * @param array $arrayData
     * @return xml
     */
    public function converXML($arrayData)
    {
        $xml = '<xml>';
        foreach($arrayData as $key => $val)
        {
            $xml .= '<'.$key.'><![CDATA['.$val.']]></'.$key.'>';
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * @brief 从xml到array转换数据格式
     * @param xml $xmlData
     * @return array
     */
    public function converArray($xmlData)
    {
        $result = array();
        $xmlHandle = xml_parser_create();
        xml_parse_into_struct($xmlHandle, $xmlData, $resultArray);

        foreach($resultArray as $key => $val)
        {
            if($val['tag'] != 'XML')
            {
                $result[$val['tag']] = $val['value'];
            }
        }
        return array_change_key_case($result);
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    private function createLinkstring($para)
    {
        $arg  = "";
        foreach($para as $key => $val)
        {
            $arg.=$key."=".$val."&";
        }

        //去掉最后一个&字符
        $arg = trim($arg,'&');

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc())
        {
            $arg = stripslashes($arg);
        }

        return $arg;
    }
    
    /**
     * 随机生成32位字符串
     * @return type
     */
    public function getRandomStr(){
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
        $str = str_shuffle($str);
        $str = substr($str,0,32);
        return  $str;
    }

    public function getSign($params) {
        ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
        $prestr = $this->createLinkstring($params);
        
        $mach_key =  $this->config['wx_mch_key'];
        
        //把拼接后的字符串再与安全校验码直接连接起来
        $prestr = $prestr.'&key=' . $mach_key;
        //把最终的字符串签名，获得签名结果
        $mysgin = md5($prestr);
        return strtoupper($mysgin);
    }
    
    /**
     * 获取微信app支付字符串
     * 
     * @param type $order_no
     * @param type $amount
     * @param type $notify_url
     * @param type $body
     * @param type $openid
     * @param type $trade_type
     * @return boolean
     */
    public function getWechatPayStr($order_no, $amount, $notify_url, $body, $openid = '', $trade_type = WechatPayService::JSAPI)
    {
        $nonce_str = $this->getRandomStr();                     //调用随机字符串生成方法获取随机字符串
        $data['appid'] = $this->config['wx_app_id'];            //appid
        $data['mch_id'] = $this->config['wx_mch_id'];              //商户号
        $data['body'] =  $body;
        $data['spbill_create_ip'] = request()->getClientIp();   //ip地址
        $data['total_fee'] = $amount * 100;                     //金额
        $data['out_trade_no'] = $order_no;                      //商户订单号,不能重复
        $data['nonce_str'] = $nonce_str;                        //随机字符串
        $data['notify_url'] = $notify_url;                      //回调地址,用户接收支付后的通知,必须为能直接访问的网址,不能跟参数
        $data['trade_type'] = $trade_type;                      //支付方式
        
        //1：普通支付，2：第3方授权账户 ,3：绑定平台APP商户, 4：普通店铺
        if ($this->config['type'] == 3) {
            $openid_key = 'sub_openid';
            $data['appid'] = $this->config['component_appid'];
            $data['sub_appid'] = $this->config['wx_app_id'];
            $data['sub_mch_id'] = $this->config['wx_mch_id'];
        }else if ($this->config['type'] == 2) {
            $openid_key = 'sub_openid';
            $data['appid'] = $this->config['component_appid'];
            $data['sub_appid'] = $this->config['authorizer_app_id'];
            $data['appid'] = $this->config['authorizer_app_id'];
            $data['sub_mch_id'] = $this->config['wx_mch_id'];
        }
        else {
            $openid_key = 'openid';
        }
        if($trade_type == self::JSAPI) {
            $data[$openid_key] = $openid;
        }
        
        //将参与签名的数据保存到数组  注意：以上几个参数是追加到$data中的，$data中应该同时包含开发文档中要求必填的剔除sign以外的所有数据
        //获取签名
        $data['sign'] = $this->getSign($data);
        
        //数组转xml
        $xml = $this->converXML($data);
        $data = $this->curlSubmit($xml);
        
        //返回结果
        if($data){
            //返回成功,将xml数据转换为数组.
            $re = $this->converArray($data);
            if($re['return_code'] != 'SUCCESS'){
                BLogger::getLogger(BLogger::LOG_WX_PAY)->info($re);
                return false;
            }
            else{
                return $this->getPayResult($trade_type, $re['prepay_id'], $nonce_str);
            }
        } else {
            return false;
        }
    }
    
    //返回结果通用设置
    public function getPayResult($type, $prepayid, $noncestr) {
        //接收微信返回的数据,传给APP!
        if ($this->config['type'] == 2) {
            $app_id = $this->config['authorizer_app_id'];
        } else{
            $app_id = $this->config['wx_app_id'];
        }
        switch($type){
            case WechatPayService::JSAPI:
                $arr =array(
                    'appId' => $app_id,
                    'timeStamp' => time(),
                    'nonceStr' => $noncestr,
                    'package' => 'prepay_id='. $prepayid,
                    'signType' => 'MD5'
                );
                //第二次生成签名
                $arr['paySign'] = $this->getSign($arr);
                break;
            case WechatPayService::APP:
            default :
                $arr =array(
                    'prepayid' =>$prepayid,
                    'appid' => $app_id,
                    'partnerid' => $this->config['mch_id'],
                    'package' => 'Sign=WXPay',
                    'noncestr' => $noncestr,
                    'timestamp' => time(),
                );
                //第二次生成签名
                $arr['sign'] = $this->getSign($arr);
        }
        
        return $arr;
    }
    
    
    /**
     * 获取微信支付二维码
     * @param type $order_no
     * @param type $amount
     * @param type $notify_url
     * @param type $body
     * @return boolean
     */
    public function getQrCode($order_no, $amount, $notify_url, $body = '')
    {
        $nonce_str = $this->getRandomStr();                        //调用随机字符串生成方法获取随机字符串
        $data['appid'] = $this->config['wx_app_id'];                //appid
        $data['mch_id'] = $this->config['wx_mch_id'];              //商户号
        $data['body'] = "云乐享车 " . $body;
        $data['spbill_create_ip'] = $_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR']: '47.106.11.143';    //ip地址
        $data['total_fee'] = $amount * 100;                     //金额
        $data['out_trade_no'] = $order_no;                      //商户订单号,不能重复
        $data['nonce_str'] = $nonce_str;                        //随机字符串
        $data['notify_url'] = $notify_url;                      //回调地址,用户接收支付后的通知,必须为能直接访问的网址,不能跟参数
        $data['trade_type'] = 'NATIVE';                            //支付方式
        
        //将参与签名的数据保存到数组  注意：以上几个参数是追加到$data中的，$data中应该同时包含开发文档中要求必填的剔除sign以外的所有数据
        //获取签名
        $data['sign'] = $this->getSign($data);
        
        //数组转xml
        $xml = $this->converXML($data);
        $data = $this->curlSubmit($xml);
        
        //返回结果
        if($data){
            //返回成功,将xml数据转换为数组.
            $re = $this->converArray($data);
            if($re['return_code'] == 'SUCCESS' && $re['result_code'] == 'SUCCESS'){
                //接收微信返回二维码字符串
                return $re['code_url'];
            }
        }
        return false;
    }
    
    /**
     * 获取微信支付订单支付状态
     * @param type $order_no
     */
    public function tradeStatusQuery($order_no) {
        if(empty($order_no)) {
            return false;
        }
        $nonce_str = $this->getRandomStr();                        //调用随机字符串生成方法获取随机字符串
        $data['appid'] = $this->config['wx_app_id'];                //appid
        $data['mch_id'] = $this->config['wx_mch_id'];              //商户号
        $data['out_trade_no'] = $order_no;                      //商户订单号,不能重复
        $data['nonce_str'] = $nonce_str;                        //随机字符串
        
        //将参与签名的数据保存到数组  注意：以上几个参数是追加到$data中的，$data中应该同时包含开发文档中要求必填的剔除sign以外的所有数据
        //获取签名
        $data['sign'] = $this->getSign($data);
        
        //数组转xml
        $xml = $this->converXML($data);
        $data = $this->curlSubmit($xml, 'getOrderQuery');
        
        //返回结果
        if($data){
            //返回成功,将xml数据转换为数组.
            $re = $this->converArray($data);
            if($re['return_code'] == 'SUCCESS' && $re['result_code'] == 'SUCCESS'){
                //返回交易状态情报
                /**
                 * SUCCESS—支付成功 REFUND—转入退款 
                 * NOTPAY—未支付 CLOSED—已关闭 
                 * REVOKED—已撤销（刷卡支付）USERPAYING--用户支付中 
                 * PAYERROR 支付失败(其他原因，如银行返回失败)
                 */
                return $re['trade_state'];
            }
        }
        return false;
    }
    
    /**
     * 关闭订单
     * @param type $order_no
     * @return boolean
     */
    public function tradeCancel($order_no) {
        if(empty($order_no)) {
            return false;
        }
        $nonce_str = $this->getRandomStr();                        //调用随机字符串生成方法获取随机字符串
        $data['appid'] = $this->config['wx_app_id'];                //appid
        $data['mch_id'] = $this->config['wx_mch_id'];              //商户号
        $data['out_trade_no'] = $order_no;                      //商户订单号,不能重复
        $data['nonce_str'] = $nonce_str;                        //随机字符串
        
        //获取签名
        $data['sign'] = $this->getSign($data);
        
        //数组转xml
        $xml = $this->converXML($data);
        $data = $this->curlSubmit($xml, 'getCloseOrder');
        
        //返回结果
        if($data){
            //返回成功,将xml数据转换为数组.
            $re = $this->converArray($data);
            if($re['return_code'] == 'SUCCESS'){
                if($re['result_code'] == 'SUCCESS' || $re['result_code'] == 'FAIL' && $re['err_code'] == 'ORDERCLOSED')
                {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * 订单退款接口
     * @param type $out_trade_no  //本地支付流水号
     * @param type $amount 订单金额
     * @param type $refund_amount 退款金额
     * @return boolean
     */
    public function tradeRefund($out_trade_no, $amount, $refund_amount)
    {
        if(empty($out_trade_no)) {
            return false;
        }
        $nonce_str = $this->getRandomStr();                         //调用随机字符串生成方法获取随机字符串
        $data['appid'] = $this->config['wx_app_id'];                //appid
        $data['mch_id'] = $this->config['wx_mch_id'];               //商户号
        $data['out_trade_no'] = $out_trade_no;                      //商户订单号,不能重复
        $data['nonce_str'] = $nonce_str;                            //随机字符串
        $data['out_refund_no'] = $out_trade_no;                     //订单退款流水号, 和订单交易号一致
        $data['total_fee'] = $amount * 100;
        $data['refund_fee'] = $refund_amount * 100;
        $data['refund_desc'] = "【{$out_trade_no}】订单退款";
        
        //获取签名
        $data['sign'] = $this->getSign($data);
        
        //数组转xml
        $xml = $this->converXML($data);
        $data = $this->curlSubmit($xml, 'getPayRefund', true);
        
        //返回结果
        if(!!$data){
            //返回成功,将xml数据转换为数组.
            $re = $this->converArray($data);
            if($re['return_code'] == 'SUCCESS' && $re['result_code'] == 'SUCCESS'){
                return true;
            }
        }
        return false;
    }
}
