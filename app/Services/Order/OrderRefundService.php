<?php
namespace App\Services\Order;

//use App\Services\Jpush\Jpush;
use App\Services\Aliyun\Alipush;
use App\Models\PayOrders;
use App\Libraries\BLogger;
use App\Jobs\PushOrderJob;
use App\Models\Orders;

/**
 * OrderRefund 订单退款
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-12-10 12:12:55
 * @copyright   Copyright(C) kbftech Inc.
 */
class OrderRefundService {
    protected $payOrderModel = null;
    protected $orderModel = null;

    public function __construct() {
        $this->payOrderModel = app()->make('App\Models\PayOrders');
        $this->orderModel = app()->make('App\Models\Orders');
    }
    
    /**
     * 发送push推送
     * 
     * @param type $order_id
     * @return boolean
     */
    public function refundOrderPay($order_id)
    {
        $payOrder = $this->payOrderModel->getDataByOrderId($order_id);
        
        if (!$payOrder || $payOrder->order->state != 96) {
            BLogger::getLogger(BLogger::LOG_REFUND)->error("订单【{$order_id}】,不存在, 或者不是退款订单。");
            return false;
        }
        
        //处理消息体
        switch ($payOrder->pay_method) {
            case 'wechat':
                $payService = app()->make('App\Services\Wechat\WechatPayService');
                $result = $payService->tradeRefund($payOrder->pay_order_no, $payOrder->payment_amount, $payOrder->payment_amount);
                break;
            case 'alipay':
                $payService = app()->make('App\Services\Aliyun\Alipay');
                $result = $payService->tradeRefund($payOrder->pay_order_no, $payOrder->payment_amount);
                break;
            default:
                return true;
                break;
        }
        
        //退款请求发送
        if ($result == true) {
            $msg = "order_id: {$order_id}, 支付退款请求操作成功。";
            BLogger::getLogger(BLogger::LOG_REFUND)->error($msg);
            //修改订单状态
            $rs = $this->orderModel->refundOrderComplate($payOrder->order_id);
            $msg = "order_id: {$order_id}, 退款订单，数据库状态修改操作" . (($rs === false) ? "失败。" : "成功。");
            BLogger::getLogger(BLogger::LOG_REFUND)->error($msg);
            if ($rs === false) {
                return false;
            }
        } else {
            $msg = "order_id: {$order_id}, 支付退款请求操作失败。";
            BLogger::getLogger(BLogger::LOG_REFUND)->error($msg);
            return false;
        }
        return true;
    }
    
}
