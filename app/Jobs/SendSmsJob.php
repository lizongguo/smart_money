<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Aliyun\Alidayu;

class SendSmsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $params;
    protected $type;
    
    protected $info = [
        'queue' => '您的就餐队列前方还有#{num}人等待。',
        'takeout_cancel' => '您的外卖订单#{meal_no}已取消，等待退款中，如有疑问请联系商家 #{phone}。',
        'takeout_express' => '您的外卖订单#{meal_no}正在配送中，请耐心等待，配送员#{express_name} #{express_phone}。',
        'booking_cancel' => '您的预约订单已取消，店铺：#{shop_name}，预约时间：#{booking_time}，如有疑问请联系商家 #{phone}。',
        'booking_ok' => '您的预约订单已确认，请准时就餐，店铺：#{shop_name}，预约时间：#{booking_time}。',
    ];
    
    const QUEUE_WAIT_NUM = '1';     //就餐等待短信通知
    const TAKEOUT_CANCEL = '2';     //外卖拒绝
    const TAKEOUT_EXPRESS = '3';    //外卖配送
    const BOOKING_CANCEL = '4';     //预约拒绝
    const BOOKING_OK = '5';         //预约确认
    
    protected $templateCodes = [
        self::QUEUE_WAIT_NUM => 'SMS_164511616', //小程序注册
        self::TAKEOUT_CANCEL => 'SMS_164511619', //服务员忘记密码
        self::TAKEOUT_EXPRESS => 'SMS_164506640', //服务员忘记密码
        self::BOOKING_CANCEL => 'SMS_164506645', //服务员忘记密码
        self::BOOKING_OK => 'SMS_164506648', //服务员忘记密码
    ];




    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $params, $type)
    {
        $this->phone = $phone;
        $this->params = $params;
        $this->type = $type;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sender = app()->make('App\Services\Aliyun\Alidayu');
        //推送订单push
        $sender->sendSms($this->phone, $this->params, $this->templateCodes[$this->type]);
    }
}
