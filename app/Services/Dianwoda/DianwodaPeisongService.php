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
namespace App\Services\Dianwoda;
use GuzzleHttp\Client;
use App\Libraries\BLogger;

class DianwodaPeisongService
{
    protected $settingModel = null;
    protected $setting = null;
    
    //插件名称
    public    $name = '点我达配送接口';
    protected $client = null;
    protected $app_key = null;
    protected $app_secret = null;
    protected $access_token = null;
    protected $api_uri =  'https://peisongopen.meituan.com/api/';
    protected $format = 'json';
    protected $online = false;


    protected $now = null;
    protected $config = null;
    protected $xml = null;
    
    public function __construct()
    {
        $this->settingModel = app()->make('App\Models\Setting');
        $this->setting = $this->settingModel->getSystemSetting(true);
        $this->client = new Client();
        $this->online = env('APP_ENV') == 'production' ? true : false;
        $this->app_key = $this->setting['takeout_appKey'];
        $this->app_secret = $this->setting['takeout_appSecret'];
        $this->access_token = $this->setting['takeout_sourceId'];
    }
    
    /**
     * 获取接口地址
     * @return type
     */
    protected function getUrl()
    {
        return $this->online ? "https://open.dianwoda.com/gateway" : "https://open-test.dianwoda.com/gateway";
    }


    /**
     * 基础header情报
     */
    protected function baseHeader() {
        $header = [
            'Content-Type' => 'application/json'
        ];
        return $header;
    }
    
    /**
     * 基础请求参数
     * @return type
     */
    protected function baseParams() {
        $params = [
            'appkey' => $this->app_key,
            'nonce' => random_int(10000, 99999),
            'timestamp' => (int) (microtime(true) * 1000),
            'access_token' => $this->access_token
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
    protected function getApiData(array $data = [], $method = 'POST', $format = null, $header = [])
    {
        $url = $this->getUrl();
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
        $logData['result'] = $result;
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
        $arg  = [];
        foreach($para as $key => $val)
        {
            $arg[] = $key . '=' . $val;
        }
        return implode("&", $arg);
    }
    
    /**
     * 生成签名
     * @param \App\Services\Dianwoda\type $params
     * @return type
     */
    public function getSign($params, $body)
    {   
        ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
        $preStr = $this->createSignString($params);
        
        $secretKey =  $this->app_secret;
        
        //把拼接后的字符串再与安全校验码直接连接起来
        $beforeSign = $preStr . '&body='.json_encode($body). '&secret='. $secretKey;
        
        //把最终的字符串签名，获得签名结果
        $afterSign = sha1($beforeSign);
        
        return $afterSign;
    }
    
    /**
     * 创建配送订单
     * @param \App\Services\Meituan\type $order_id 订单id
     * @param \App\Services\Meituan\type $shop 店铺情报
     * @param \App\Services\Meituan\type $receiver_name 收货人
     * @param \App\Services\Meituan\type $receiver_address 收货地址
     * @param \App\Services\Meituan\type $receiver_phone 收货电话
     * @param \App\Services\Meituan\type $receiver_lng 配送进度
     * @param \App\Services\Meituan\type $receiver_lat 配送纬度
     * @param \App\Services\Meituan\type $goods_value 商品价格
     * @param type $note
     * @param type $expected_delivery_time
     * @param \App\Services\Meituan\type $created 点餐时间
     * @param \App\Services\Meituan\type $meal_no 取餐序号
     */
    public function createOrder($order_id, $shop, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note, $expected_delivery_time, $created, $meal_no = null)
    {
        $data = $this->baseParams();
        $data['api'] = 'dianwoda.order.create';
        $body = [];
        if (!empty($meal_no)) {
            $body['serial_id'] = $meal_no;
        }
        $body['order_original_id'] = $order_id;
        $body['seller_id'] = $shop->takeout_store_code;
        $body['consignee_name'] = $receiver_name;
        $body['consignee_address'] = $receiver_address;
        $body['consignee_mobile'] = $receiver_phone;
        $body['consignee_lng'] = $receiver_lng;
        $body['consignee_lat'] = $receiver_lat;
        $body['order_price'] = $goods_value * 100;
        $body['city_code'] = env('CITY_CODE', '510100'); //默认成都
        $body['order_is_reserve'] = 1;
        $body['time_reserve_deliver_end'] = strtotime($expected_delivery_time) * 1000;
        $body['time_reserve_deliver_start'] = $body['time_reserve_deliver_end'] - 900 * 1000; //送货时间前的15分钟
        $body['order_remark'] = $note;
        $body['order_create_time'] = strtotime($created) * 1000;
        $body['seller_name'] = $shop->shop_name;
        $body['seller_mobile'] = $shop->phone;
        $body['seller_address'] = $shop->shop_address;
        $body['seller_lat'] = $shop->lat;
        $body['seller_lng'] = $shop->long;
        $body['cargo_weight'] = 0;
        $body['cargo_num'] = 1;
        $body['cargo_type'] = '00';
        
        //获取签名
        $data['sign'] = $this->getSign($data, $body);
        
        $method = 'POST';
        
        $result = $this->getApiData(['query' => $data, 'json' => $body], $method, $this->format);
        
        //创建成功
        if ($result['code'] == 'success') {
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
    public function cancelOrder($order_id, $cancel_reason = '顾客主动取消')
    {
        $data = $this->baseParams();
        $data['api'] = 'dianwoda.order.cancel';
        $body = [];
        
        $body['order_original_id'] = $order_id;
        $body['cancel_type'] = 0;
        $body['cancel_reason'] = $cancel_reason;
        
        //获取签名
        $data['sign'] = $this->getSign($data, $body);
        
        $method = 'POST';
        
        $result = $this->getApiData(['query' => $data, 'json' => $body], $method, $this->format);
        
        //创建成功
        if ($result['code'] == 'success') {
            return true;
        }
        return false;
    }
    
    /**
     * 查询订单状态
     */
    public function queryOrderStatus($order_id)
    {
        $data = $this->baseParams();
        $data['api'] = 'dianwoda.order.query';
        $body = [];
        
        $body['order_original_id'] = $order_id;
        
        //获取签名
        $data['sign'] = $this->getSign($data, $body);
        
        $method = 'POST';
        
        $result = $this->getApiData(['query' => $data, 'json' => $body], $method, $this->format);
        
        //创建成功
        if ($result['code'] == 'success') {
            return $result['data'];
        }
        return false;
    }
    
    
}
