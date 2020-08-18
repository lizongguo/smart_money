<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushOrderJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $order_id;
    protected $event;
    
    /**
     * 推送事件
     */
    const CREATE = 'CREATE'; //创建订单
    const ADD = 'ADD'; //加菜操作
    const REFUND = 'REFUND'; //退菜操作
    const PAY = 'PAY'; //餐后线上结算后通知
    const COMPLETE = 'COMPLETE'; //订单已完成
    
    const TAKEOUT_CANCEL = 'TAKEOUT_CANCEL'; //外卖订单取消
    const TAKEOUT_RECEIPT = 'TAKEOUT_RECEIPT'; //外卖订单接单
    const TAKEOUT_DELIVERY = 'TAKEOUT_DELIVERY'; //服务员接单
    const TAKEOUT_COMPLATE = 'TAKEOUT_COMPLATE'; //送餐完成
    const ORDER_GOODS_STATE = 'ORDER_GOODS_STATE'; //订单上菜确认
    
    const BOOKING_CREATE = 'BOOKING_CREATE'; //预约订单
    const BOOKING_CANCEL = 'BOOKING_CANCEL'; //用户预约取消
    const BOOKING_OK = 'BOOKING_OK'; //预约确认
    const BOOKING_NO = 'BOOKING_NO'; //预约拒绝
    const BOOKING_EAT = 'BOOKING_EAT'; //预约就餐

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id, $event = self::CREATE)
    {
        $this->order_id = $order_id;
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orderPush = app()->make('App\Services\Order\OrderPushService');
        //订单推送
        if (in_array($this->event, ['CREATE', 'ADD', 'REFUND', 'PAY', 'COMPLETE', 
            'TAKEOUT_CANCEL', 'TAKEOUT_RECEIPT', 'TAKEOUT_DELIVERY', 'TAKEOUT_COMPLATE', 'ORDER_GOODS_STATE']))
        {
            //推送订单push
            $orderPush->sendPush($this->order_id, $this->event);
        }
        else if (in_array($this->event, ['BOOKING_CREATE', 'BOOKING_CANCEL', 'BOOKING_OK', 'BOOKING_NO', 'BOOKING_EAT']))
        {
            //推送订单push
            $orderPush->sendBookingPush($this->order_id, $this->event);
        }
    }
}
