<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DB;
use App\Libraries\BLogger;
use App\Services\Wechat\WechatPayService;
use App\Services\Aliyun\AlipayService;
use App\Jobs\PushOrderJob;
use App\Jobs\SendSmsJob;
use Illuminate\Support\Facades\Redis;

class Orders extends BaseModel
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function shops() : belongsTo
    {
        return $this->belongsTo(Shops::class, 'shop_id');
    }
    
    /**
     * 扩展列表查询
     * @param type $sh 查询条件
     * @param type $all 是否全部查询
     * @param type $limit 每页数
     * @param type $field 查询字段
     * @return type
     */
    public function getList($sh=[], $all = false, $limit = 20, $field = null)
    {
        $rs = parent::getList($sh, $all, $limit, $field);
        foreach($rs as &$item){
            $item->shops = $item->shops()->pluck('shop_name');
            $item->orderType = config('code.order_type')[$item->order_type];
            $item->payType = config('code.pay_type')[$item->pay_type];
            $item->stateStr = config('code.order_state')[$item->state];
        }
        return $rs;
    }
    
    public function getOrders($currentUser, $search = [])
    {
        $limit = $search['limit'];
        
        $sh = [
            'orders.deleted' => 0,
            'shop_id' => $search['shop_id']
        ];
        
        //会员用户只能查看自己的订单
        if ($currentUser['role'] == 2) {
            $sh['user_id'] = $currentUser['id'];
        } elseif (!empty($search['user_id'])) {
            $sh['user_id'] = $search['user_id'];
        }
        
        if(!empty($search['meal_no'])) {
            $sh['meal_no'] = $search['meal_no'];
        }
        
        if($search['max'] > 0) {
            $sh[$search['orderby']] = ['conn' => '<', 'value' => $search['max']];
        }
        
        if($search['min'] > 0) {
            $sh[$search['orderby']] = ['conn' => '>', 'value' => $search['min']];
        }
        
        if ($search['type'] == 1) {
            $sh['is_takeout'] = 0;
        } else if ($search['type'] == 2) {
            $sh['is_takeout'] = 1;
        }
        
//        if(!empty($search['sdate']) && !empty($search['edate'])){
//            $sh['created_at'] = ['conn' => 'between', 'value' => [$search['sdate'] . " 00:00:00", $search['edate'] . " 23:59:59"]];
//        }elseif(!empty($search['sdate'])) {
//            $sh['created_at'] = ['conn' => '>=', 'value' => $search['sdate'] . " 00:00:00"];
//        }elseif(!empty($search['edate'])) {
//            $sh['created_at'] = ['conn' => '<=', 'value' => $search['edate'] . " 23:59:59"];
//        }
        
        if(!empty($search['sdate'])) {
            $sh['created_at'] = ['conn' => 'between', 'value' => [$search['sdate'] . " 00:00:00", $search['sdate'] . " 23:59:59"]];
        }
        
        $obj = $this->select(
            'id','order_no','shop_id','user_id', 'username', 'desk_id', 'desk_alias', 'meal_no',
            'pay_type', 'pay_state', 'order_type','is_takeout', 'memo','goods_num', 'queue_id', 'booking_id',
            'total_amount', 'preferential_amount','payment_amount', 'state', 'completion_time', 'created_at')
            ->orderBy($search['orderby'], 'desc')
            ->where(function($query){
                $query->where('is_takeout', 0)->orWhere(function($q){
                    $q->where('is_takeout', 1)->where('state', '>', 1);
                });
            });
        $this->parseSh($obj, $sh);
        
        $data = $obj->limit($limit)->get();
        return $data;
    }
    
    /**
     * 获取订单详细
     * @param type $id
     */
    public function view($id)
    {
        $data = $this->select(
            'id','order_no','shop_id','user_id', 'username', 'desk_id', 'desk_alias', 'meal_no',
            'pay_type', 'pay_state', 'order_type','is_takeout', 'memo','goods_num', 'queue_id', 'booking_id',
            'total_amount', 'preferential_amount','payment_amount', 'state', 'completion_time', 'created_at'
            )
            ->where('id', $id)
            ->first();
        if(!$data) {
            return false;
        }
        return $data;
    }
    
    
    protected function getMaxMealNoByDayOrShopId($shop_id, $day)
    {
        $result = $this->select(DB::raw('max(meal_no) as max'))
            ->where('shop_id', $shop_id)
            ->whereBetween('created_at', [$day . " 00:00:00", $day . " 23:59:59"])
            ->first();
        return $result->max;
    }
    
    public function getMealNoByShopId($shop_id)
    {
        $redisKey = sprintf(config('rediskeys.order_meal_no'), $shop_id);
        //如果存在，自增+1
        if(Redis::exists($redisKey)) {
            return Redis::incr($redisKey);
        } else {
            $max = $this->getMaxMealNoByDayOrShopId($shop_id, date('Y-m-d'));
            Redis::set($redisKey, $max+1);
            //设置今日23点59分59秒过期
            Redis::expireat($redisKey, strtotime(date('Y-m-d 23:59:59')));
            return $max+1;
        }
    }

    public function createOrder ($shop, $setting, $user, $data, $device_type)
    {   
        $data['order_no'] = $this->randomOrderSN();
        $data['meal_no'] = 0;
        $data['total_amount'] = 0.00; //总金额
        $data['preferential_amount'] = 0.00; //折扣金额
        $data['payment_amount'] = 0.00; //支付金额
        $data['goods_num'] = 0; //订单数量
        $bookingModel = new Booking();
        $day = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        
        $specs_ids = [0];
        foreach($data['goodsList'] as $goods) {
            $specs_ids[] = $goods['goods_specs_id'];
        }
        $productModel = new GoodsSpecs();
        $specs = $productModel->getSpecsByIds($specs_ids);
        $insertOrderGoods = [];
        foreach($data['goodsList'] as $goods) {
            if(!isset($specs[$goods['goods_specs_id']])) {
                continue;
            }
            $item = [
                'order_id' => 0,
                'shop_id' => $data['shop_id'],
                'goods_id' => $specs[$goods['goods_specs_id']]->goods_id,
                'goods_name' => $specs[$goods['goods_specs_id']]->goods_name,
                'img' => $specs[$goods['goods_specs_id']]->img,
                'goods_specs_id' => $goods['goods_specs_id'],
                'sell_price' => $specs[$goods['goods_specs_id']]->sell_price,
                'spec_str' => $specs[$goods['goods_specs_id']]->spec_str,
                'goods_num' => $goods['goods_num'],
                'return_num' => 0,
                'buy_times' => 0,
                'state' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ];
            $data['goods_num'] += $goods['goods_num'];
            $data['total_amount'] += $specs[$goods['goods_specs_id']]->sell_price * intval($goods['goods_num']);
            $insertOrderGoods[] = $item;
        }
        
        if (count($insertOrderGoods) < 1) {
            return false;
        }
        //线上
        $order = null;
        if ($setting['postprandial_settlement']  == '1' && $data['types'] != 'takeout') {
            switch ($data['types']) {
                case 'scan':
                    $order = $this->where('desk_id', $data['desk_id'])->where('state', 1)->first();
                    if (!$order) {
                        $data['meal_no'] =  $this->getMealNoByShopId($shop['id']); //生成取餐号
                    }
                    break;
                case 'queue':
                    $order = $this->where('queue_id', $data['queue_id'])->where('state', 1)->first();
                    if (!$order) {
                        $data['meal_no'] =  $this->getMealNoByShopId($shop['id']); //生成取餐号
                    }
                    break;
                case 'booking':
                    $booking = $bookingModel->where('id', $data['booking_id'])->first();
                    if (!$booking) {
                        return false;
                    }
                    $booking_day = date('Y-m-d', strtotime($booking->booking_time));
                    if ($booking_day < $day) {
                        return false;
                    }
                    $order = $this->where('booking_id', $data['booking_id'])->where('state', 1)->first();
                    if (!$order) {
                        //当天直接生成取餐号
                        if ($booking_day == $day) {
                            $data['meal_no'] =  $this->getMealNoByShopId($shop->id); //生成取餐号
                        } else {
                            //后期的通过查询数据库获取当日最大的meal_no
                            $data['meal_no'] =  $this->getMaxMealNoByDayOrShopId($shop->id, $booking_day) + 1;
                        }
                        $data['created_at'] = $booking->booking_time;
                    }
                    break;
            }
        }
        
        if ($data['types'] == 'takeout') {
            $takeout = [
                'takeout_type' => $data['takeout_type'],
                'take_cate' => $setting['other_service_state'] == 1 ? 2 : 1,
                'waiter_id' => 0,
                'accept_name' => (string)$data['takeout_data']['accept_name'],
                'gender' => intval($data['takeout_data']['gender']),
                'phone' => $data['takeout_data']['phone'],
                'delivery_time' => $data['takeout_data']['delivery_time'] ? $data['takeout_data']['delivery_time'] : null,
                'delivery_address' => (string)$data['takeout_data']['delivery_address'],
                'long' => $data['takeout_data']['long'] ? $data['takeout_data']['long'] : 0,
                'lat' => $data['takeout_data']['lat'] ? $data['takeout_data']['lat'] : 0,
                'state' => 0, //自动接单
                'takeout_amount' => $data['takeout_type'] ==1 ? $setting['takeout_price'] : 0,
                'tableware_amount' => $setting['takeout_tableware_price'] *  $data['goods_num']
            ];
            //总金额追加配送费 和 餐具费
            $data['total_amount'] = $data['total_amount'] + $takeout['takeout_amount'] + $takeout['tableware_amount'];
        }
        
        $data['payment_amount'] = $data['total_amount'];
        
        DB::beginTransaction();
        try {
            if (!!$order) {
                $data['id'] = $order->id;
                $data['payment_amount'] += $order->payment_amount;
                $data['total_amount'] += $order->total_amount;
                $data['goods_num'] += $order->goods_num;
                unset($data['meal_no'], $data['user_id'], $data['username']);
            }
            $order_id = $this->saveItem($data);
            $data['id'] = $order_id;
            
            if ($data['types'] == 'takeout') {
                $takeoutModel = new OrderTakeout();
                $takeout['order_id'] = $order_id;
                $takeout_id = $takeoutModel->saveItem($takeout);
            }
            
            
            $orderGoodsModel = new OrderGoods();
            
            //保存order good 情报
            if (!!$order) {
                //订单存在合并订单商品
                /*
                $orderGoodsList = $orderGoodsModel->getOrderGoodsByOrderIds($order->id);
                $goodsList = [];
                foreach ($orderGoodsList[$order->id] as $goodsItem) {
                    $goodsList[$goodsItem->goods_specs_id] = $goodsItem;
                }
                $newInsertOrderGoods = [];
                foreach ($insertOrderGoods as $orderGoods) {
                    if (isset($goodsList[$orderGoods['goods_specs_id']])) {
                        $orderGoods['id'] = $goodsList[$orderGoods['goods_specs_id']]->id;
                        $orderGoods['goods_num'] = $orderGoods['goods_num'] + $goodsList[$orderGoods['goods_specs_id']]->goods_num;
                        $orderGoods['return_num'] = $goodsList[$orderGoods['goods_specs_id']]->return_num;
                        $orderGoodsModel->where('id', $orderGoods['id'])->update($orderGoods);
                    } else {
                        $newInsertOrderGoods[] = $orderGoods;
                    }
                }
                
                if (count($newInsertOrderGoods) > 0) {
                    $orderGoodsModel->insertBatch($newInsertOrderGoods);
                }
                */
                //获取最大的购买次数
                $max_buy_times = $orderGoodsModel->select(DB::raw('max(buy_times) as times'))->where('order_id', $order->id)->first();
                $buy_times = $max_buy_times->times + 1;
                foreach($insertOrderGoods as $k => $item) {
                    $item['order_id'] = $order_id;
                    $item['buy_times'] = $buy_times;
                    $insertOrderGoods[$k] = $item;
                }
            } else {
                foreach($insertOrderGoods as  $k => $item) {
                    $item['order_id'] = $order_id;
                    $insertOrderGoods[$k] = $item;
                }
            }
            //插入订单菜品清单
            $orderGoodsModel->insertBatch($insertOrderGoods);
            
            
            //保存活动
            if (!empty($data['activity_id'])) {
                $orderActivity = new OrderActivity();
                $discount_amount = $orderActivity->setOrderActivityByOrderIdAndActivityId($order_id, $data['activity_id']);
                if ($discount_amount === false) {
                    return false;
                }
                else {
                    //更新优惠金额
                    $data['payment_amount'] = $data['total_amount'] - $discount_amount;
                    $data['preferential_amount'] = $discount_amount;
                    $this->saveItem($data);
                }
            }
            
            //支付功能 餐前支付 或者 外卖
            if($setting['postprandial_settlement'] != 1 || $data['types'] == 'takeout') 
            { 
                //请求统一支付接口
                if ($device_type == 'wechat') {
                    $payService = new WechatPayService();
                    //通知地址
                    $notify = route('callback.orderNotify', ['paymethod' => 'wechat']);
                    $function = 'getWechatPayStr';
                    $openid = $user['wx_open_id'];
                    $trade_type = WechatPayService::JSAPI;
                } elseif($device_type == 'alipay') {
                    $payService = new AlipayService();
                    $notify = route('callback.orderNotify', ['paymethod' => 'alipay']);
                    $function = 'getAlipayStr';
                    $openid = $user['ali_open_id'];
                    $trade_type = null;
                } else {
                    return false;
                }
                
                $payOrder = new PayOrders();
                //生成支付订单
                $result = $payOrder->createPayOrder($order_id, $user, $device_type, $data['total_amount'], $data['preferential_amount'], $data['payment_amount']);
                if ($result == false) {
                    throw new \Exception('创建支付订单失败。');
                }
                //调用支付接口，创建预支付订单
                $pay_data = $payService->$function(
                    $result['pay_order_no'],
                    $result['payment_amount'],
                    $notify,
                    $shop->shop_name . '' . date('Ymd') ." 自主点餐",
                    $openid,
                    $trade_type
                );
                if(!$pay_data) {
                    throw new \Exception('创建支付订单失败。');
                }
                //保存预支付情报
                $payOrder->where('id', $result['id'])->update(['paymentdata' => json_encode($pay_data)]);
            }
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        //发送push通知 餐后支付，切非外卖订单
        if ($setting['postprandial_settlement'] == 1 && $data['is_takeout'] != '1') {
            dispatch(new PushOrderJob($order_id, !!$order ? PushOrderJob::ADD : PushOrderJob::CREATE));
        }
        
        $order = $this->getOne($order_id)->toArray();
        
        $return = [
            'id' => $order_id,
            'shop_id' => $shop['id'],
            'order_no' => $order['order_no'],
            'desk_id'  => $order['desk_id'],
            'desk_alias' => $order['desk_alias'],
            'user_id'  => $order['user_id'],
            'username'  => $order['username'],
            'goods_num' => $order['goods_num'],
            'queue_id' => $order['queue_id'],
            'booking_id' => $order['booking_id'],
            'order_type' => $order['order_type'],
            'is_takeout' => $order['is_takeout'],
            'state' => $order['state'],
            'created_at' => $order['created_at'],
        ];
        
        if (isset($pay_data) && !!$pay_data) {
            $return['paymentdata'] = $pay_data;
        }
        return $return;
    }
    
    /**
     * 支付结算功能
     * 
     * @param type $order
     * @param type $activity_id
     * @param type $user
     * @param type $device_type
     * @return boolean
     * @throws \Exception
     */
    public function orderPayment($order, $activity_id, $user, $device_type)
    {
        $orderActivity = new OrderActivity();
        
        $shop = $order->shops;
        
        DB::beginTransaction();
        try {
            //创建活动信息表
            $discount_amount = $orderActivity->setOrderActivityByOrderIdAndActivityId($order->id, $activity_id);
            if ($discount_amount === false) {
                return false;
            }
            
            $data = [
                'id' => $order->id,
                'total_amount' => $order->total_amount,
                'payment_amount' => $order->total_amount - $discount_amount,
                'preferential_amount' => $discount_amount
            ];
            //保存优惠金额
            $order_id = $this->saveItem($data);
            
            //支付功能
            //请求统一支付接口
            if ($device_type == 'wechat') {
                $payService = new WechatPayService();
                //通知地址
                $notify = route('callback.orderNotify', ['paymethod' => 'wechat']);
                $function = 'getWechatPayStr';
                $openid = $user['wx_open_id'];
                $trade_type = WechatPayService::JSAPI;
            } elseif($device_type == 'alipay') {
                $payService = new AlipayService();
                $notify = route('callback.orderNotify', ['paymethod' => 'alipay']);
                $function = 'getAlipayTradeCreate';
                $openid = $user['ali_open_id'];
                $trade_type = null;
            } else {
                return false;
            }

            $payOrder = new PayOrders();
            //生成支付订单
            $result = $payOrder->createPayOrder($order_id, $user, $device_type, $data['total_amount'], $data['preferential_amount'], $data['payment_amount']);
            if ($result == false) {
                throw new \Exception('创建支付订单失败。');
            }
            //调用支付接口，创建预支付订单
            $pay_data = $payService->$function(
                $result['pay_order_no'],
                $result['payment_amount'],
                $notify,
                $shop->shop_name . '' . date('Ymd') ." 自主点餐",
                $openid,
                $trade_type
            );
            if(!$pay_data) {
                throw new \Exception('创建支付订单失败。');
            }
            //保存预支付情报
            $payOrder->where('id', $result['id'])->update(['paymentdata' => json_encode($pay_data)]);
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        $return = [
            'id' => $order->id,
            'shop_id' => $order->shop_id,
            'order_no' => $order->order_no,
            'desk_id'  => $order->desk_id,
            'desk_alias' => $order->desk_alias,
            'user_id'  => $order->user_id,
            'username'  => $order->username,
            'goods_num' => $order->goods_num,
            'queue_id' => $order->queue_id,
            'booking_id' => $order->booking_id,
            'order_type' => $order->order_type,
            'is_takeout' => $order->is_takeout,
            'state' => $order->state,
            'created_at' => $order->created_at,
            'paymentdata' => $pay_data
        ];
        
        return $return;
    }
    
    /**
     * 管理者 线下设定 订单已完成
     * @param type $order
     * @param type $user
     * @return boolean
     * @throws \Exception
     */
    public function offlinePay($order, $user)
    {   
        DB::beginTransaction();
        try {
            $data = [
                'id' => $order->id,
                'pay_type' => 1, //线下支付
                'state' => '99', //已完成
                'completion_time' => date('Y-m-d H:i:s')
            ];
            //保存设定
            $order_id = $this->saveItem($data);
            
            $payOrder = new PayOrders();
            //生成支付订单
            $result = $payOrder->createPayOrder($order_id, $user, 'offline', $order->total_amount, $order->preferential_amount, $order->payment_amount);
            
            if ($result == false) {
                throw new \Exception('操作订单失败。');
            }
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        //推送消息 外卖拒绝接单
        dispatch(new PushOrderJob($order_id, PushOrderJob::COMPLETE));
        
        return true;
    }
    
    /**
     * 拒绝外卖订单
     * @param type $order
     * @param type $user
     * @return boolean
     */
    public function cancelTakeout($order, $user)
    {
        DB::beginTransaction();
        try {
            $data = [
                'id' => $order->id,
                'state' => '96', //已完成
            ];
            //保存设定
            $order_id = $this->saveItem($data);
            
            //修改外卖订单状态
            $orderTakeoutModel = new OrderTakeout();
            $takeout = $orderTakeoutModel->where('order_id', $order->id)->first();
            $takeout->takeout_state = 96;
            $takeout->save();
//            
//            $orderTakeoutModel->where('order_id', $order->id)->update([
//                'takeout_state' => 96 //拒绝接单
//            ]);
            
            $takeoutInfoModel = new TakeoutInfo();
            //添加记录
            $takeoutInfoModel->addRecord(96, $user['username'], $order_id);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        //todo 添加队列退款任务
        dispatch(new \App\Jobs\OrderRefundJob($order_id));
        
        //发送取消 短信通知
        $params = [
            'meal_no' => "#" . $order->meal_no,
            'phone' => $order->shops->phone,
        ];
        dispatch(new SendSmsJob($takeout->phone, $params, SendSmsJob::TAKEOUT_CANCEL));
        
        //推送消息 外卖拒绝接单
        dispatch(new PushOrderJob($order_id, PushOrderJob::TAKEOUT_CANCEL));
        
        return true;
    }
    
    /**
     * 外卖接单
     * @param type $order
     * @param type $user
     * @return boolean
     */
    public function receiptTakeout($order, $user)
    {
        DB::beginTransaction();
        try {
            //修改外卖订单状态
            $orderTakeoutModel = new OrderTakeout();
            $takeout = $orderTakeoutModel->where('order_id', $order->id)->first();
            $takeout->takeout_state = 1;
            $takeout->save();
            
//            $orderTakeoutModel->where('order_id', $order->id)->update([
//                'takeout_state' => 1 //已接单
//            ]);
            
            $takeoutInfoModel = new TakeoutInfo();
            //添加记录
            $takeoutInfoModel->addRecord(1, $user['username'], $order->id);
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        $settingModel = app()->make('App\Models\Setting');
        $setting = $settingModel->whereExtend(['shop_id' => ['conn' => 'in', 'value' => [$order->shop_id, 0]]])->pluck('value', 'name')->toArray();
        //推送消息 外卖接单
        dispatch(new PushOrderJob($order->id, PushOrderJob::TAKEOUT_RECEIPT));
        
        //开通第3方配送，且为外卖配送的订单
        if ($takeout->takeout_type == 1 && $setting['other_service_state'] == 1) {
            //添加推送第三方配送 推单队列
            BLogger::getLogger(BLogger::LOG_PEISONG)->info('配送JOB已发送。');
            dispatch(new \App\Jobs\PeisongJob($order->id, \App\Jobs\PeisongJob::PEISONG_CREATE));
        }
        
        
        
        return true;
    }
    
    
    /**
     * 骑手接单
     * @param type $order
     * @param type $user
     * @return boolean
     */
    public function deliveryTakeout($order, $user)
    {
        $orderTakeoutModel = new OrderTakeout();
        $takeout = $orderTakeoutModel->whereExtend(['order_id' => $order->id, 'takeout_state' => '1', 'take_cate' => 1])->first();
        if (!$takeout) {
            return false;
        }
        
        $takeoutInfoModel = new TakeoutInfo();
        
        DB::beginTransaction();
        try {
            //修改外卖订单状态, 以及基本信息
            $orderTakeoutModel->where('id', $takeout->id)->update([
                'takeout_state' => 3, //已接单
                'waiter_id' => $user['id'],
                'express_name' => $user['username'],
                'express_phone' => $user['phone'],
            ]);
            
            //添加分配记录
            $takeoutInfoModel->addRecord(2, $user['username'], $order->id, ['express_name' => $user['username']]);
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        
        //推送消息 骑手接单配送中
        dispatch(new PushOrderJob($order->id, PushOrderJob::TAKEOUT_DELIVERY));
        
        //添加送餐中的记录
        $takeoutInfoModel->addRecord(3, $user['username'], $order->id);
        
        //发送骑手配送 短信通知
        $params = [
            'meal_no' => "#" . $order->meal_no,
            'express_name' => $user['username'],
            'express_phone' => $user['phone'],
        ];
        dispatch(new SendSmsJob($takeout->phone, $params, SendSmsJob::TAKEOUT_EXPRESS));
        
        
        return true;
    }
    
    
    /**
     * 配送完成
     * @param type $order
     * @param type $user
     * @return boolean
     */
    public function completeTakeout($order, $user)
    {
        $orderTakeoutModel = new OrderTakeout();
        $takeout = $orderTakeoutModel->whereExtend(['order_id' => $order->id, 'takeout_state' => '3', 'take_cate' => 1])->first();
        
        //配送订单不存在 或者配送员不是当前操作者
        if (!$takeout || $takeout->waiter_id != $user['id']) {
            return false;
        }
        
        $takeoutInfoModel = new TakeoutInfo();
        
        DB::beginTransaction();
        try {
            //修改订单状态为已完成
            $this->where('id', $order->id)->update([
                'state' => 99, //已完成，
            ]);
            
            //修改外卖订单状态
            $orderTakeoutModel->where('id', $takeout->id)->update([
                'takeout_state' => 99, //已完成
            ]);
            
            //添加外卖状态记录
            $takeoutInfoModel->addRecord(99, $user['username'], $order->id);
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        //推送消息 配送完成
        dispatch(new PushOrderJob($order->id, PushOrderJob::TAKEOUT_COMPLATE));
        
        return true;
    }
    
    
    /**
     * 退菜功能
     * @param type $order
     * @param type $return
     * @return boolean
     */
    public function refundOrderGoods($order, $return)
    {
        $ids = array_keys($return);
        $orderGoodsModel = new OrderGoods();
        //查询订单商品情报
        $lists = $orderGoodsModel->whereExtend([
            'order_id' => $order->id,
            'deleted' => 0,
            'id' => [
                'conn' => 'in',
                'value' => $ids,
            ]
        ])->select('id', 'goods_num', 'return_num')->get();
        
        
        DB::beginTransaction();
        try {
            
            foreach($lists as $item) {
                if ($item->return_num + $return[$item->id] > $item->goods_num) {
                    continue;
                }
                $orderGoodsModel->where('id', $item->id)->increment('return_num', $return[$item->id]);
            }
            
            //获取订单商品的总金额和总数量
            $total = $orderGoodsModel->getOrderTotalAmountAndGoodsNum($order->id);
            $this->where('id', $order->id)->update([
                'total_amount' => $total->amount,
                'goods_num' => $total->goods_num,
                'preferential_amount' => 0,
                'payment_amount' => $total->amount,
            ]);
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_WX_PAY)->info($ex);
            return false;
        }
        
        //退菜push
        dispatch(new PushOrderJob($order->id, PushOrderJob::REFUND));
        
        return true;
    }
    
    /**
     * 退款接口完成后 修改本地订单状态
     * @param type $order_id
     * @return boolean
     */
    public function refundOrderComplate($order_id)
    {
        $order = $this->getOne($order_id);
        if (!$order || $order->state != 96) {
            return false;
        }
        
        DB::beginTransaction();
        try {
            //保存订单状态为已退款
            $order->state = 97;
            $order->save();
            
            //修改支付订单单状态 为 已退款
            $payOrderModel = new PayOrders();
            $payOrderModel->where('order_id', $order_id)->where('state', 1)->update(['state' => 2]);
            
            //外卖订单 更新状态为 97 已退款
            if ($order->is_takeout == 1) {
                $orderTakeoutModel = new OrderTakeout();
                $orderTakeoutModel->where('order_id', $order_id)->where('takeout_state', 96)->update(['takeout_state' => 97]);
                
                //生成外卖退款记录
                $takeoutInfoModel = new TakeoutInfo();
                //添加外卖状态记录
                $takeoutInfoModel->addRecord(97, "系统", $order->id);
            }
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_REFUND)->error($ex);
            return false;
        }catch(\Exception $ex) {
            DB::rollback();
            BLogger::getLogger(BLogger::LOG_REFUND)->info($ex);
            return false;
        }
        
        return true;
    }
    
}
