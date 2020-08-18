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
namespace App\Services\Dada;
use App\Libraries\BLogger;
use App\Services\Dada\Sdk\config\Config;
use App\Services\Dada\Sdk\api\AddOrderApi;
use App\Services\Dada\Sdk\api\CancelApi;
use App\Services\Dada\Sdk\api\QueryApi;
use App\Services\Dada\Sdk\client\DadaRequestClient;
use App\Services\Dada\Sdk\client\DadaResponse;
use App\Services\Dada\Sdk\config\DadaConstant;
use App\Services\Dada\Sdk\model\OrderModel;

class DadaPeisongService implements DadaPeisong
{
    protected $settingModel = null;
    protected $setting = null;
    
    //插件名称
    public    $name = '达达配送接口';
    protected $client = null;
    protected $app_id = null;
    protected $app_secret = null;
    protected $online = null;

    protected $now = null;
    protected $config = null;
    
    public function __construct()
    {
        $this->settingModel = app()->make('App\Models\Setting');
        $this->setting = $this->settingModel->getSystemSetting(true);
        $this->online = env('APP_ENV') == 'production' ? true : false;
        $this->config = new Config($this->setting['takeout_sourceId'], $this->setting['takeout_appKey'], $this->setting['takeout_appSecret'], $this->online);
    }
    
    /**
     * 创建配送订单
     * @param \App\Services\Meituan\type $order_id 订单id
     * @param \App\Services\Meituan\type $shop_id 店铺id
     * @param \App\Services\Meituan\type $receiver_name 收货人
     * @param \App\Services\Meituan\type $receiver_address 收货地址
     * @param \App\Services\Meituan\type $receiver_phone 收货电话
     * @param \App\Services\Meituan\type $receiver_lng 配送进度
     * @param \App\Services\Meituan\type $receiver_lat 配送纬度
     * @param \App\Services\Meituan\type $goods_value 商品价格
     * @param type $note
     */
    public function createOrder($order_id, $shop_id, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note)
    {
        $orderModel = new OrderModel();
        $orderModel->setShopNo($this->online ? $shop_id : '11047059'); //测试时默认店铺
        $orderModel->setOriginId($order_id);
        $orderModel->setCityCode(env('CITY_CODE', '028')); //城市编号
        $orderModel->setCargoPrice($goods_value);
        $orderModel->setIsPrepay(0);
        $orderModel->setInfo($note);
        $orderModel->setReceiverName($receiver_name);
        $orderModel->setReceiverAddress($receiver_address);
        $orderModel->setReceiverLat($receiver_lat);
        $orderModel->setReceiverLng($receiver_lng);
        $orderModel->setReceiverPhone($receiver_phone);
        
        //达达回调
        $orderModel->setCallback(route('callback.takeout', ['method' => 'dada']));

        //*********************3.实例化一个api*************************
        $addOrderApi = new AddOrderApi(json_encode($orderModel));

        //***********************4.实例化客户端请求************************
        $dada_client = new DadaRequestClient($this->config, $addOrderApi);
        $result = $dada_client->makeRequest();
        BLogger::getLogger(BLogger::LOG_PEISONG)->info([$result->status, $result->status == DadaConstant::SUCCESS, $result->result]);
        //创建成功
        if ($result->status == DadaConstant::SUCCESS) {
            return $result->result;
        }
        return false;
    }
    
    /**
     * 取消配送
     * @param type $order_id $order_no
     * @param type $mt_peisong_id 美团配送id
     * @param type $cancel_reason_id 取消id
     * @param type $cancel_reason   取消原因
     * @return boolean
     */
    public function cancelOrder($order_id, $cancel_reason_id='101', $cancel_reason = '顾客主动取消')
    {
        $cancelModel = new \App\Services\Data\Sdk\model\CancelModel();
        $cancelModel->setOrderId($order_id);
        $cancelModel->setCancelReasonId($cancel_reason_id);
        $cancelModel->setCancelReason($cancel_reason);
        

        //*********************3.实例化一个api*************************
        $cancelApi = new CancelApi(json_encode($cancelModel));

        //***********************4.实例化客户端请求************************
        $dada_client = new DadaRequestClient($this->config, $cancelApi);
        $result = $dada_client->makeRequest();
        
        //取消成功
        if ($result->status == DadaConstant::SUCCESS) {
            return $result->result;
        }
        return false;
    }
    
    /**
     * 查询订单状态
     */
    public function queryOrderStatus($order_id)
    {
        $model = new \App\Services\Data\Sdk\model\QueryModel();
        $model->setOrderId($order_id);
        

        //*********************3.实例化一个api*************************
        $api = new \App\Services\Data\Sdk\api\QueryApi(json_encode($model));

        //***********************4.实例化客户端请求************************
        $dada_client = new DadaRequestClient($this->config, $api);
        $result = $dada_client->makeRequest();
        
        //取消成功
        if ($result->status == DadaConstant::SUCCESS) {
            return $result->result;
        }
        return false;
    }
    
    /**
     * 测试回调状态
     */
    public function test($order_id, $status)
    {
        $map = [
            2 => '/api/order/accept',
            3 => '/api/order/fetch',
            4 => '/api/order/finish',
            5 => '/api/order/cancel',
            7 => '/api/order/expire',
        ];
        $d = ['order_id' => $order_id];
        
        if ($status == '5') {
            $d['reason'] = '测试';
        }
        
        //*********************3.实例化一个api*************************
        $api = new Sdk\api\BaseApi($map[$status], json_encode($d));

        //***********************4.实例化客户端请求************************
        $dada_client = new DadaRequestClient($this->config, $api);
        $result = $dada_client->makeRequest();
        
        //取消成功
        if ($result->status == DadaConstant::SUCCESS) {
            return $result->result;
        }
        return false;
    }
    
}
