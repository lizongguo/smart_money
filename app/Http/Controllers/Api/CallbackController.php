<?php
/**
 * callback Controller
 *
 * @package       Api.Controller
 * @author        yutlong
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) kbftech Inc.
 */

namespace App\Http\Controllers\Api;
use DB;
use App\Models\Orders;
use App\Libraries\BLogger;
use App\Models\PayOrders;
use App\Services\Wechat\WechatPayService;
use App\Services\Aliyun\Alipay;
use App\Jobs\PushOrderJob;
use App\Jobs\PeisongJob;
use Illuminate\Http\Request;

class CallbackController extends BaseController
{
    protected $order, $payOrder, $alipayService, $wxpayService, $settingModel;
    protected $setting;

    public function __construct(Request $request, Orders $order,  PayOrders $payOrder, Alipay $alipayService, WechatPayService $wxpayService)
    {
        parent::__construct($request);
        $this->order = $order;
        $this->payOrder = $payOrder;
        $this->alipayService = $alipayService;
        $this->wxpayService = $wxpayService;
        $this->settingModel = app()->make('App\Models\Setting');
    }
    
    /**
     * 配送推送回调结果
     * @param Request $request
     * @param type $method
     */
    public function takeoutNotify(Request $request, $method)
    {
        if(!in_array($method, ['dada', 'dianwoda'])) {
            exit("fail");
        }
        
        if($method == 'dianwoda') { //点我达
            $callbackData = file_get_contents('php://input');
            //保存日志
            BLogger::getLogger(BLogger::LOG_PEISONG)->info(['method' => $method, 'data' => $callbackData]);
            $dataArr = json_decode($callbackData, true);
            $dataArr = $dataArr['content'];
            $statusMap = [
                'created' => 1,
                'dispatched' => 2,
                'arrived' => 3,
                'obtained' => 3,
                'completed' => 4,
                'abnormal' => 10,
                'canceled' => 10,
            ];
            
            $order_no = $dataArr['order_original_id'];
            $status = $statusMap[$dataArr['order_status']];
            $express_name = $dataArr['rider_name'];
            $express_phone = $dataArr['rider_mobile'];
            $last_callback_time = intval(((int)$dataArr['time_status_update']) / 1000);
            
        } else if ($method == 'dada') { //达达
            $callbackData = file_get_contents('php://input');
            //保存日志
            BLogger::getLogger(BLogger::LOG_PEISONG)->info(['method' => $method, 'data' => $callbackData]);
            $dataArr = json_decode($callbackData, true);
            $map = [
                'client_id' => (string) $dataArr['client_id'],
                'order_id' => (string) $dataArr['order_id'],
                'update_time' => (string) $dataArr['update_time'],
            ];
            sort($map);
            $sign = md5(implode('', $map));
            if ($sign !== $dataArr['signature']) {
                //保存
                BLogger::getLogger(BLogger::LOG_PEISONG)->info('签名错误。');
                return response('fail', '403');
            }
            $order_no = $dataArr['order_id'];
            $status = $dataArr['order_status'];
            $express_name = $dataArr['dm_name'];
            $express_phone = $dataArr['dm_mobile'];
            $last_callback_time = $dataArr['update_time'];
        }
        
        //获取订单情报
        $orderRow  = $this->order->where('order_no', $order_no)->where('is_takeout', 1)->first();
        if(!$orderRow)
        {
            BLogger::getLogger(BLogger::LOG_PEISONG)->info("order sn:{$order_no},订单不存在。");
            return response('fail', '403');
        }
        
        $takeoutInfoModel = new \App\Models\TakeoutInfo();
        $takeoutModel = new \App\Models\OrderTakeout();
        $takeout = $takeoutModel->getTakeoutByOrderId($orderRow->id);
        
        if ($method == 'dada' || $method == 'dianwoda') {
            if ($takeout->last_callback_time > $last_callback_time) {
                return response('success', '200');
            }
            DB::beginTransaction();
            try {
                //待接单＝1,待取货＝2,配送中＝3,已完成＝4,已取消＝5, 已过期＝7,指派单=8,妥投异常之物品返回中=9, 妥投异常之物品返回完成=10,骑士到店=100,创建达达运单失败=1000
                switch ($status) {
                    case 1: //推单完成
                        $save = [
                            'takeout_push_state' => 2,
                            'last_callback_time' => $last_callback_time,
                        ];
                        $takeoutModel->where('order_id', $orderRow->id)->update($save);
                        break;
                    case 2:
                        $save = [
                            'takeout_state' => 2,
                            'express_name' => $express_name,
                            'express_phone' => $express_phone,
                            'last_callback_time' => $last_callback_time,
                        ];
                        $takeoutModel->where('order_id', $orderRow->id)->update($save);
                        //添加记录
                        $takeoutInfoModel->addRecord(2, "配送平台：{$express_name}", $orderRow->id, $save);
                        break;
                    case 3:
                        $takeoutModel->where('order_id', $orderRow->id)->update([
                            'takeout_state' => 3,
                            'last_callback_time' => $last_callback_time,
                        ]);
                        //添加记录
                        $takeoutInfoModel->addRecord(3, "配送平台：{$express_name}", $orderRow->id);
                        break;

                    case 4:
                        $this->order->where('id', $orderRow->id)->update(['state' => 99]);
                        $takeoutModel->where('order_id', $orderRow->id)->update([
                            'takeout_state' => 99,
                            'last_callback_time' => $last_callback_time,
                        ]);
                        //添加记录
                        $takeoutInfoModel->addRecord(99, "平台", $orderRow->id);
                        break;
                    case 5:
                    case 9: //异常返回物品
                    case 10: //返回物品成功
                        $takeoutModel->where('order_id', $orderRow->id)->update([
                            'takeout_state' => 98,
                            'last_callback_time' => $last_callback_time,
                        ]);
                        //添加记录
                        $takeoutInfoModel->addRecord(98, "平台", $orderRow->id);
                        break;
                    case 1000: //创建失败

                        $takeoutModel->where('order_id', $orderRow->id)->update([
                            'takeout_state' => 1,
                            'last_callback_time' => 0,
                        ]);
                        //添加记录
                        $takeoutInfoModel->addRecord(98, "平台", $orderRow->id);
                        //重新发布订单
                        //添加推送第三方配送 推单队列
                        dispatch(new \App\Jobs\PeisongJob($orderRow->id, \App\Jobs\PeisongJob::PEISONG_CREATE));
                    default:
                        break;
                }
                DB::commit();
                return response('success', '200');
            }catch(\Illuminate\Database\QueryException $ex) {
                DB::rollback();
                BLogger::getLogger(BLogger::LOG_PEISONG)->info("配送订单[{$orderRow->id}]订单处理失败。");
                BLogger::getLogger(BLogger::LOG_PEISONG)->info($ex);
                return response('fail', '500');
            }
        }
        
    }
    
    
    /**
     * 订单推送报价回调结果
     * @param Request $request
     * @param type $paymethod
     */
    public function orderNotify(Request $request, $paymethod)
    {
        if(!in_array($paymethod, ['alipay', 'wechat'])) {
            exit("fail");
        }
        
        if($paymethod == 'alipay') {
            $callbackData = $_POST;
            //保存日志
            BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info(['paymethod' => $paymethod, 'data' => $callbackData]);
            
            $payService = $this->alipayService;
            $verify_result = $payService->rsaCheckV1($callbackData);
            if($verify_result == false) {
                BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info('支付宝签名验证失败。');
                exit("fail");
            }
            
            $is_success = false;
            if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $is_success = true;
            }
            
            $pay_order_no = $_POST['out_trade_no'];
            $total_amount = $_POST['total_amount'] * 100;
            $trade_no = $_POST['trade_no'];
        } else {
            $payService = $this->wxpayService;
            $callbackData = file_get_contents('php://input');
            $callbackData = $payService->converArray($callbackData);
            $pay_order_no = $callbackData['out_trade_no'];
            
            //保存日志
            BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info(['paymethod' => $paymethod, 'data' => $callbackData]);
            $sign = $callbackData['sign'];
            unset($callbackData['sign']);
            $verify_result = ($sign == $payService->getSign($callbackData)) ? true : false;
            if($verify_result == false) {
                BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info('微信支付回调签名验证失败。');
                exit("fail");
            }
            
            $is_success = false;
            if (isset($callbackData['return_code']) && $callbackData['return_code'] == 'SUCCESS' && isset($callbackData['result_code']) && $callbackData['result_code'] == 'SUCCESS') {
                $is_success = true;
            }
            $total_amount = $callbackData['total_fee'];
            $trade_no = $callbackData['transaction_id'];
        }
        
        //获取订单情报
        $orderRow  = $this->payOrder->getDataByOrderNo($pay_order_no);
        if(!$orderRow)
        {
            BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info("order no【{$pay_order_no}】支付订单不存在。");
            if($paymethod == 'wechat') {
                $payService->notifyStop();
            }
            exit("fail");
        }
        
        $this->setting = $this->settingModel->getShopSettingByCategory($orderRow->order->shop_id, '外卖配置');
        
        //取整
        $orderPay = intval(100 * $orderRow->payment_amount);
        if (intval($total_amount) != $orderPay)
        {
            BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info("PAY ORDER ID【{$orderRow->order_id}】,订单金额不正确, 订单金额（{$orderRow->payment_amount}元）, 回调金额（{$total_amount}分）。");
            exit("fail");
        }
        
        //订单已处理完毕
        if ($orderRow->state == 1) {
            BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info("[{$orderRow->order_id}]订单已回调成功。");
            if($paymethod == 'wechat') {
                $payService->notifyStop();
            }
            exit("success");
        }
        
        if ($is_success) {
            DB::beginTransaction();
            try {
                //更新支付状态
                $this->payOrder->where('pay_order_no', $pay_order_no)->update(['state' => 1, 'trade_no' => $trade_no]);
                
                //更新订单状态 、取餐号
                $save = [
                    'pay_state' => 1, //已支付
                    'pay_type' => 2,
                    'state' => 2,
                ];
                
                if ($orderRow->order->order_type == 1) {
                    $save['meal_no'] = $this->order->getMealNoByShopId($orderRow->order->shop_id);
                } else if ($orderRow->order->order_type == 2){
                    $save['state'] = 99; //餐后支付 设置为已完成
                    $save['completion_time'] = date('Y-m-d H:i:s');
                }
                
                $this->order->where('id', $orderRow->order_id)->update($save);
                
                //外卖订单， 添加记录
                if ($orderRow->order->is_takeout == 1) {
                    $takeoutInfoModel = new \App\Models\TakeoutInfo();
                    //添加待接单记录
                    $takeoutInfoModel->addRecord(0, $orderRow->order->username, $orderRow->order_id, ['payment_amount' => $orderRow->payment_amount]);

                    //自动接单
                    if ($this->setting['takeout_auto_receipt'] == 1) {
                        //修改外卖订单状态 为 已接单
                        $orderTakeout  = new \App\Models\OrderTakeout();
                        $takeout = $orderTakeout->getTakeoutByOrderId($orderRow->order_id);

                        $orderTakeout->where('order_id', $orderRow->order_id)->update(['state' => 1]);
                        //添加待接单记录
                        $takeoutInfoModel->addRecord(1, "系统", $orderRow->order_id);
                        //开通第3方配送，且为外卖配送的订单
                        if ($takeout->takeout_type == 1 && $this->setting['other_service_state'] == 1) {
                            //添加配送请求队列
                            dispatch(new PeisongJob($orderRow->order_id, PeisongJob::PEISONG_CREATE));
                        }
                    }
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $ex) {
                DB::rollback();
                BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info("[{$orderRow->order_id}]订单处理失败。");
                BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info($ex);
                exit("fail");
            }
            
            BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info("[{$orderRow->order_id}]订单处理成功。");
            
            //发送push通知 餐后支付，切非外卖订单
            if ($orderRow->order->order_type == 2 && $orderRow->order->is_takeout == '1' || $orderRow->order->order_type == 1) {
                dispatch(new PushOrderJob($orderRow->order_id, PushOrderJob::CREATE));
            } else if ($orderRow->order->order_type == 2) { //用户支付后，推送给收银员
                dispatch(new PushOrderJob($orderRow->order_id, PushOrderJob::PAY));
                dispatch(new PushOrderJob($orderRow->order_id, PushOrderJob::COMPLETE));
            }
            
            if($paymethod == 'wechatpay') {
                $payService->notifyStop();
            }
            exit("success");
        } else {
            //更新订单状态为支付失败
            BLogger::getLogger(BLogger::LOG_ORDER_CALLBACK)->info("[{$orderRow->order_id}]订单支付失败。");
            //支付失败，自动取消订单
            $this->payOrder->where('id', $orderRow->id)->update(['state' => '98']);
//            $this->order->where('id', $orderRow->order_id)->update(['state' => '98', 'cancel_reason' => '支付失败，系统自动取消订单。']);
            
            if($paymethod == 'wechatpay') {
                $payService->notifyStop();
            }
            exit("success");
        }
        exit("fail");
    }
    
}
