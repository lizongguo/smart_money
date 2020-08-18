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
namespace App\Services\Meituan;
use GuzzleHttp\Client;
use App\Libraries\BLogger;

class PeisongService implements Peisong
{
    protected $settingModel = null;
    protected $setting = null;
    
    //插件名称
    public    $name = '美团配送接口';
    protected $client = null;
    protected $app_id = null;
    protected $app_secret = null;
    protected $api_uri =  'https://peisongopen.meituan.com/api/';
    protected $format = 'json';


    protected $now = null;
    protected $config = null;
    protected $xml = null;
    
    public function __construct()
    {
        $this->settingModel = app()->make('App\Models\Setting');
        $this->setting = $this->settingModel->getSystemSetting(true);
        $this->client = new Client();
    }
    
    
    /**
     * 基础header情报
     */
    protected function baseHeader() {
        $header = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8'
        ];
        return $header;
    }
    
    /**
     * 基础请求参数
     * @return type
     */
    protected function baseParams() {
        $params = [
            'appkey' => $this->setting['takeout_appKey'],
            'version' => '1.0',
            'timestamp' => time(),
        ];
        return $params;
    }


    /**
     * 获取接口相关接口数据
     * 
     * @param type $path
     * @param array $data
     * @param type $method
     * @param type $format
     * @param type $header
     * @return boolean
     */
    protected function getApiData($path, array $data = [], $method = 'POST', $format = null, $header = [])
    {
        $url = $this->api_uri . ltrim($path, '/');
        $method = strtoupper($method);
        
        $verify = true;
        if(preg_match("#^https#is", $url)) {
            $verify = false;
        }
        
        $opts = [
            'headers' => array_merge($this->baseHeader(), $header),
            'http_errors' => false,
            'timeout' => 20,
            'version' => 1.1,
        ];
        
        if(is_array($data) && count($data)) {
            $opts = array_merge($opts, $data);
        }
        
        $verify == false ? $opts['verify'] = false : '';
        $logData = [
            'method' => $method,
            'uri' => $url,
            'data' => $data
        ];
        $response = $this->client->request(
            $method,
            $url,
            $opts
        );
        if ($response->getStatusCode() != '200') {
            $logData['result'] = [
                'code' => $response->getStatusCode(), 
                'Response' => $response->getBody()
            ];
            BLogger::getLogger(BLogger::LOG_PEISONG)->error($logData);
            return false;
        }
        $result = (string) $response->getBody();
        if($format == 'json' && preg_match('/^{.*}$/is', $result)) {
            $result = json_decode($result, true);
            //解析json数据
            if (json_last_error() != JSON_ERROR_NONE) {
                $logData['result'] = 'json error:' + json_last_error();
                BLogger::getLogger(BLogger::LOG_PEISONG)->error($logData);
                return false;
            }
        }
        $logData['result'] = 'SUCCESS';
        BLogger::getLogger(BLogger::LOG_PEISONG)->info($logData);
        return $result;
    }
    
    /**
     * 把数组所有元素，按照"参数""参数值"的模式，拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    private function createSignString($para)
    {
        $arg  = "";
        foreach($para as $key => $val)
        {
            $arg .=$key . $val;
        }

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc())
        {
            $arg = stripslashes($arg);
        }

        return $arg;
    }
    
    /**
     * 生成签名
     * @param type $params
     * @return type
     */
    public function getSign($params)
    {
        ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
        $preStr = $this->createSignString($params);
        
        $secretKey =  $this->config['takeout_appSecret'];
        
        //把拼接后的字符串再与安全校验码直接连接起来
        $beforeSign = $secretKey . $preStr;
        
        //把最终的字符串签名，获得签名结果
        $afterSign = strtolower(sha1($beforeSign));
        
        return $afterSign;
    }
    
    /**
     * 创建配送订单
     * @param \App\Services\Meituan\type $delivery_id 唯一id
     * @param \App\Services\Meituan\type $order_id 订单id
     * @param \App\Services\Meituan\type $shop_id 店铺id
     * @param \App\Services\Meituan\type $receiver_name 收货人
     * @param \App\Services\Meituan\type $receiver_address 收货地址
     * @param \App\Services\Meituan\type $receiver_phone 收货电话
     * @param \App\Services\Meituan\type $receiver_lng 配送进度
     * @param \App\Services\Meituan\type $receiver_lat 配送纬度
     * @param \App\Services\Meituan\type $goods_value 商品价格
     * @param type $note
     * @param type $expected_delivery_time
     */
    public function createOrder($delivery_id, $order_id, $shop_id, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note, $expected_delivery_time)
    {
        $data = $this->baseParams();
        
        $data['delivery_id'] = $delivery_id;
        $data['order_id'] = $order_id;
        $data['shop_id'] = $shop_id;
        $data['receiver_name'] = $receiver_name;
        $data['receiver_address'] = $receiver_address;
        $data['receiver_phone'] = $receiver_phone;
        $data['receiver_lng'] = $receiver_lng;
        $data['receiver_lat'] = $receiver_lat;
        $data['goods_value'] = $goods_value;
        $data['note'] = $note;
        $data['expected_delivery_time'] = strtotime($expected_delivery_time);
        
        $data['sign'] = $this->getSign($data);
        
        $method = 'POST';
        $path = 'order/createByShop';
        
        $result = $this->getApiData($path, ['form_params' => $data], $method, $this->format);
        
        //创建成功
        if ($result['code'] == '0') {
            return $result['data'];
        }
        return false;
    }
    
    
    /**
     * 取消配送
     * @param type $delivery_id 唯一id
     * @param type $mt_peisong_id 美团配送id
     * @param type $cancel_reason_id 取消id
     * @param type $cancel_reason   取消原因
     * @return boolean
     */
    public function cancelOrder($delivery_id, $mt_peisong_id, $cancel_reason_id='101', $cancel_reason = '顾客主动取消')
    {
        $data = $this->baseParams();
        
        $data['delivery_id'] = $delivery_id;
        $data['mt_peisong_id'] = $mt_peisong_id;
        $data['cancel_reason_id'] = $cancel_reason_id;
        $data['cancel_reason'] = $cancel_reason;
        
        $data['sign'] = $this->getSign($data);
        
        $method = 'POST';
        $path = 'order/delete';
        
        $result = $this->getApiData($path, ['form_params' => $data], $method, $this->format);
        
        //取消成功
        if ($result['code'] == '0') {
            return $result['data'];
        }
        return false;
    }
    
    /**
     * 查询订单状态
     */
    public function queryOrderStatus($delivery_id, $mt_peisong_id)
    {
        $data = $this->baseParams();
        
        $data['delivery_id'] = $delivery_id;
        $data['mt_peisong_id'] = $mt_peisong_id;
        
        $data['sign'] = $this->getSign($data);
        
        $method = 'POST';
        $path = 'order/status/query';
        
        $result = $this->getApiData($path, ['form_params' => $data], $method, $this->format);
        
        //查询成功
        if ($result['code'] == '0') {
            return $result['data'];
        }
        return false;
    }
    
    
}
