<?php

/**
 * 美团配送 
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-16 17:05:16
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Services\Meituan;

interface Peisong
{
    public function createOrder($delivery_id, $order_id, $shop_id, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note, $expected_delivery_time);
    public function cancelOrder($delivery_id, $mt_peisong_id, $cancel_reason_id, $cancel_reason);
    public function queryOrderStatus($delivery_id, $mt_peisong_id);
    
}
