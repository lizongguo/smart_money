<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Libraries\BLogger;

class PeisongJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $order_id;
    protected $type;
    protected $takeoutModel;


    const PEISONG_CREATE = '1';         //推送订单
    const PEISONG_CANCEL = '2';         //取消配送订单
    const PEISONG_QUEUE_STATUS = '3';   //查询订单状态

    
    /**
     * Create a new 配送 job instance.
     * 
     * @param type $order_id
     * @param type $type
     */
    public function __construct($order_id, $type = PeisongJob::PEISONG_CREATE)
    {
        $this->order_id = $order_id;
        $this->type = $type;
        $this->takeoutModel = app()->make('App\Models\OrderTakeout');
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $takeout = $this->takeoutModel->getTakeoutByOrderId($this->order_id);
        if (!$takeout || $takeout->takeout_type != 1 || $takeout->takeout_push_state != 0) {
            //
            BLogger::getLogger(BLogger::LOG_PEISONG)->info("外卖订单id：{$this->order_id}，不存在失败, 或者已推送。");
            return false;
        }
        switch ($this->type) {
            case self::PEISONG_CREATE:
                $result = $this->createPeisong($takeout);
                break;
            default:
                return false;
//                break;
        }
        
        if ($result === false) {
            BLogger::getLogger(BLogger::LOG_PEISONG)->info("订单id：{$takeout->order_id}, express_code:{$result['mt_peisong_id']} 配送操作保存失败。");
            return false;
        }
        
        BLogger::getLogger(BLogger::LOG_PEISONG)->info("订单id：{$takeout->order_id}, express_code:{$result['mt_peisong_id']} 配送操作成功。");
        return true;
    }
    
    
    /**
     * 第3方配送 推单操作
     * @param type $takeout
     * @return boolean
     */
    protected function createPeisong($takeout)
    {
        $settingModel = app()->make('App\Models\Setting');
        $setting = $settingModel->getSystemAndShopSetting($takeout->order->shop_id);
        if (empty($setting['takeout_store_code'])) {
            BLogger::getLogger(BLogger::LOG_PEISONG)->info("外卖订单id：{$this->order_id}，未设置门店编号无法推送配送订单。");
            return false;
        }
        
        $delivery_id = $takeout->id;
        $order_id = $takeout->order->order_no;
        $shop_id = $setting['takeout_store_code'];
        $receiver_name = $takeout->accept_name;
        $receiver_address = $takeout->delivery_address;
        $receiver_phone = $takeout->phone;
        $receiver_lng = $takeout->long;
        $receiver_lat = $takeout->lat;
        $goods_value = $takeout->order->payment_amount;
        $note = $takeout->order->memo;
        $expected_delivery_time = strtotime($takeout->delivery_time);
        
        //推送第3方外卖配送订单
//        $result = $this->sender->createOrder($delivery_id, $order_id, $shop_id, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note, $expected_delivery_time);
        
        $express_code = '';
        if ($setting['takeout_company'] == 'dada') { //达达
            $this->sender = app()->make('App\Services\Dada\DadaPeisong');
            $result = $this->sender->createOrder($order_id, $shop_id, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note);
        } else if ($setting['takeout_company'] == 'dianwoda') { //点我达
            $this->sender = app()->make('App\Services\Dianwoda\DianwodaPeisongService');
            $shop = $takeout->order->shops;
            $shop->takeout_store_code = $shop_id;
            $result = $this->sender->createOrder($order_id, $shop, $receiver_name, $receiver_address, $receiver_phone, $receiver_lng, $receiver_lat, $goods_value, $note, $expected_delivery_time, $takeout->order->created_at, $takeout->order->meal_no);
            $express_code = isset($result['dwd_order_id']) ? $result['dwd_order_id'] : '';
        }
        if ($result === false) {
            BLogger::getLogger(BLogger::LOG_PEISONG)->info("订单id：{$takeout->order_id}创建配送订单失败。");
            return false;
        }
        
        //保存数据
        $save = [
            'express_code' => $express_code,
            'takeout_push_state' => '1',
        ];
        
        $rs = $this->takeoutModel->where('id', $takeout->id)->update($save);
        
        return !!$rs ? $result : false;
    }
}
