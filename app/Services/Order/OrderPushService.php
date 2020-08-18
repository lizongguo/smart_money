<?php
namespace App\Services\Order;

//use App\Services\Jpush\Jpush;
use App\Services\Aliyun\Alipush;
use App\Models\Waiter;
use App\Libraries\BLogger;
use App\Jobs\PushOrderJob;
use App\Models\Orders;
use App\Models\Booking;

/**
 * OrderPush
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-12-10 12:12:55
 * @copyright   Copyright(C) kbftech Inc.
 */
class OrderPushService {
    protected $pusher = null;
    protected $waiter = null;
    protected $order = null;
    protected $pusherType = 'aliyun';

    public function __construct(Alipush $pusher, Waiter $waiter, Orders $order) {
        $this->pusher = $pusher;
        $this->waiter = $waiter;
        $this->order = $order;
    }
    
    /**
     * 发送push推送
     * 
     * @param type $order_id
     * @return boolean
     */
    public function sendPush($order_id,  $event = PushOrderJob::CREATE)
    {
        $order = $this->order->getOne($order_id);
        if (!$order) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error("订单【{$order_id}】,不存在");
            return false;
        }
        
        //查询店铺员工厨师的用户
        $tokens = [];
        $tokenArr = [];
        $this->getTokens($order->shop_id, $event, $tokens, $tokenArr);
        
        //token不存在，直接返回
        if(count($tokens) < 1) {
            return true;
        }
        
        $extras = [
            'order_id' => $order->id, //订单id
            'order_no' => $order->order_no, //订单号
            'meal_no' => $order->meal_no, //当天编号
            'is_takeout' => $order->is_takeout, //外卖标记
            'event' => $event //事件
        ];
        
        $type = 'alert';
        //处理消息体
        switch ($event) {
            case PushOrderJob::CREATE :
                //发送push通知
                $message = "您收到一个新订单,订单号【{$order->order_no}】,取餐号为【{$order->meal_no}】。";
                $title = "您收到一个新订单";
                break;
            case PushOrderJob::ADD :
                //发送push通知
                $message = "您收到一个加菜的订单,订单号【{$order->order_no}】,取餐号为【{$order->meal_no}】。";
                $title = "您收到一个订单加菜通知";
                break;
            case PushOrderJob::REFUND :
                //发送push通知
                $type = 'message';
                $message = "订单号【{$order->order_no}】, 取餐号【{$order->meal_no}】, 有退菜操作。";
                $title = "您收到一个订单退菜通知";
                break;
            
            case PushOrderJob::PAY :
                //发送push通知
                $message = "订单号【{$order->order_no}】, 取餐号【{$order->meal_no}】, 用户已成功支付。";
                $title = "您收到一个订单支付完成通知";
                break;
            case PushOrderJob::COMPLETE :
                //发送push通知
                $message = "订单号【{$order->order_no}】, 取餐号【{$order->meal_no}】, 用户已成功支付, 订单已完成。";
                $title = "您收到一个订单已完成通知";
                break;
            
            case PushOrderJob::ORDER_GOODS_STATE :
                //发送push通知
                $type = 'message';
                $message = "您收到一个上菜状态变更通知,订单号【{$order->order_no}】,取餐号为【{$order->meal_no}】。";
                $title = "您收到一个上菜状态变更通知。";
                break;
            case PushOrderJob::TAKEOUT_CANCEL :
                //发送push通知
                $type = 'message';
                $message = "您收到一个外卖取消订单,订单号【{$order->order_no}】,取餐号为【{$order->meal_no}】。";
                $title = "您收到一个外卖取消订单通知。";
                break;
            case PushOrderJob::TAKEOUT_RECEIPT :
                //发送push通知
                $type = 'message';
                $message = "订单号【{$order->order_no}】, 取餐号【{$order->meal_no}】, 服务员已接单。";
                $title = "您收到一个外卖接单订单通知。";
                break;
            case PushOrderJob::TAKEOUT_DELIVERY :
                //发送push通知
                $type = 'message';
                $message = "订单号【{$order->order_no}】, 取餐号【{$order->meal_no}】, 服务员配送中。";
                $title = "您收到一个外卖订单,订单配送中。";
                break;
            case PushOrderJob::TAKEOUT_COMPLATE :
                //发送push通知
                $type = 'message';
                $message = "订单号【{$order->order_no}】, 取餐号【{$order->meal_no}】, 订单已配送完成。";
                $title = "您收到一个外卖订单,订单已配送完成。";
                break;
            default:
                break;
        }
        
        if ($type != 'alert') {
            $message = json_encode($extras);
            $extras = [];
        }
        
        if ($this->pusherType == 'aliyun') {
            //aliyun push
            $result = true;
            foreach ($tokenArr as $device_type => $token) {
                $rs = $this->pusher->pushMemberMessage($token, $message, $type, $extras, $title, $device_type);
                $msg = "order_id: {$order_id}, {$device_type} 推送发送" . (($rs === false) ? "失败。" : "成功。");
                if ($rs === false) {
                    BLogger::getLogger(BLogger::LOG_PUSH)->error($msg);
                    $result = false;
                    continue;
                }
                BLogger::getLogger(BLogger::LOG_PUSH)->info($msg);
            }
        }else {
            //jpush
            $result = $this->pusher->pushMemberMessage($tokens, $message, 'alert', $extras, $title);
        }
        
        $msg = "order_id: {$order_id}, 推送发送" . (($result === false) ? "失败。" : "成功。");
        if ($result === false) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($msg);
            return false;
        }
        BLogger::getLogger(BLogger::LOG_PUSH)->info($msg);
        return true;
    }
    
    /**
     * sendBookingPush
     * 
     * @param type $queue_id
     * @return boolean
     */
    public function sendBookingPush($queue_id,  $event = PushOrderJob::BOOKING_CREATE)
    {
        $this->booking = new Booking();
        $booking = $this->booking->getOne($queue_id);
        if (!$booking) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error("预约id【{$queue_id}】,不存在");
            return false;
        }
        
        //查询店铺员工厨师的用户
        $tokens = [];
        $tokenArr = [];
        $this->getTokens($booking->shop_id, $event, $tokens, $tokenArr);
        
        //token不存在，直接返回
        if(count($tokens) < 1) {
            return true;
        }
        
        $extras = [
            'booking_id' => $booking->id, //预约id
            'event' => $event //事件
        ];
        
        $type = 'alert';
        //处理消息体
        switch ($event) {
            case PushOrderJob::BOOKING_CREATE :
                //发送push通知
                $message = "您收到一个新预约消息，预约时间【".date('Y-m-d H:i', strtotime($booking->booking_time))."】。";
                $title = "您收到一个新预约通知";
                break;
            
            case PushOrderJob::BOOKING_CANCEL :
                //发送push通知
                $message = "您收到一个用户预约取消消息，预约时间【".date('Y-m-d H:i', strtotime($booking->booking_time))."】。";
                $title = "您收到一个用户预约取消通知";
                break;
            case PushOrderJob::BOOKING_OK :
                //发送push通知
                $message = "您收到一个用户预约确认消息，预约时间【".date('Y-m-d H:i', strtotime($booking->booking_time))."】。";
                $title = "您收到一个预约确认通知";
                break;
            case PushOrderJob::BOOKING_NO :
                //发送push通知
                $message = "您收到一个预约被拒消息，预约时间【".date('Y-m-d H:i', strtotime($booking->booking_time))."】。";
                $title = "您收到一个预约被拒通知";
                break;
            case PushOrderJob::BOOKING_EAT :
                //发送push通知
                $message = "您收到一个预约就餐消息，预约时间【".date('Y-m-d H:i', strtotime($booking->booking_time))."】。";
                $title = "您收到一个预约就餐通知";
                break;
            default:
                return false;
        }
        
        if ($type != 'alert') {
            $message = json_encode($extras);
            $extras = [];
        }
        
        if ($this->pusherType == 'aliyun') {
            //aliyun push
            $result = true;
            foreach ($tokenArr as $device_type => $token) {
                $rs = $this->pusher->pushMemberMessage($token, $message, $type, $extras, $title, $device_type);
                $msg = "booking_id: {$queue_id}, {$device_type} 推送发送" . (($rs === false) ? "失败。" : "成功。");
                if ($rs === false) {
                    BLogger::getLogger(BLogger::LOG_PUSH)->error($msg);
                    $result = false;
                    continue;
                }
                BLogger::getLogger(BLogger::LOG_PUSH)->info($msg);
            }
        }else {
            //jpush
            $result = $this->pusher->pushMemberMessage($tokens, $message, 'alert', $extras, $title);
        }
        
        $msg = "booking_id: {$queue_id}, 推送发送" . (($result === false) ? "失败。" : "成功。");
        if ($result === false) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($msg);
            return false;
        }
        BLogger::getLogger(BLogger::LOG_PUSH)->info($msg);
        return true;
    }
    
    /**
     * 获取推送tokens
     * @param type $shop_id
     * @param type $event
     * @param type $tokens
     * @param type $tokenArr
     */
    protected function getTokens($shop_id, $event, &$tokens, &$tokenArr)
    {
        //查询店铺员工厨师的用户
        $cookUser = $this->waiter->getUserByShopId($shop_id);
        foreach($cookUser as $user) {
            if (empty($user->push_token)) {
                continue;
            }
            
            //支付成功的推送 只推送给收银权限的员工
            if ($event == PushOrderJob::PAY && $user->cash_permission != 1) {
                continue;
            }
            
            $tokens[] = $user->push_token;
            $tokenArr[$user->device_type][] = $user->push_token;
        }
    }
    
}
