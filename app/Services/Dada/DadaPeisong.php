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
namespace App\Services\Dada;

interface DadaPeisong
{
    public function createOrder($order_id, $shop_id, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note);
    public function cancelOrder($order_id, $cancel_reason_id, $cancel_reason);
    public function queryOrderStatus($order_id);
    public function test($order_id, $status);
    
}
