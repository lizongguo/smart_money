<?php
/**
 * upload Controller
 *
 * @package       Api.Controller
 * @author        lee
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) bravesoft Inc.
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Shops;
use App\Models\Desks;
use App\Models\OrderGoods;
use App\Jobs\PushOrderJob;
use Validator;
use Illuminate\Validation\Rule;

class OrderController extends BaseController
{
    protected $model = null;
    protected $shop = null;
    protected $desk = null;
    protected $orderGoodsModel = null;

    public function __construct(Request $request, Orders $order, OrderGoods $orderGoods, Shops $shop, Desks $desk)
    {
        $this->model = $order;
        $this->shop = $shop;
        $this->desk = $desk;
        $this->orderGoodsModel = $orderGoods;
        parent::__construct($request);
    }
    
    /**
     * 订单列表
     * @param $request
     * @return type
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $data['limit'] = $data['limit'] < 1 ? 9999 : $data['limit'];
        $validator = Validator::make($data, [
            'shop_id' => 'required|exists:shops,id',
            'orderby' => 'nullable|in:id,meal_no',
            'user_id' => 'nullable|exists:users,id',
            'max' => 'nullable|integer',
            'min' => 'nullable|integer',
            'limit' => 'nullable|integer',
            'sdate' => 'nullable|date',
//            'edate' => 'nullable|date',
            'meal_no' => 'nullable|integer',
            'type' => 'nullable|in:0,1,2',
        ]);
        
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = '参数传递有误。';
            return $this->dataToJson($this->back);
        }
        
        
        #获取订单
        $orders = $this->model->getOrders($this->user, $data);
        $ids = [];
        foreach($orders as $order) {
            $ids[] = $order->id;
        }
        
        $list = [];
        if(count($ids) > 0) {
            
            #获取商品详细
            $orderGoodsArr = $this->orderGoodsModel->getOrderGoodsByOrderIds($ids);
            
            #获取外卖信息
            $takeoutModel = new \App\Models\OrderTakeout();
            $orderTakeoutArr = $takeoutModel->getOrderTakeoutByOrderIds($ids);
            
            #获取优惠活动信息
            $activityModel = new \App\Models\OrderActivity();
            $orderActivityArr = $activityModel->getOrderActivityByOrderIds($ids);
            
            foreach($orders as $order) {
                $order->meal_no = intval($order->meal_no);
                $order->goods = isset($orderGoodsArr[$order->id]) ? $orderGoodsArr[$order->id] : [];
                if ($order->is_takeout == 1) {
                    $order->takeout = isset($orderTakeoutArr[$order->id]) ? $orderTakeoutArr[$order->id] : new \stdClass();
                }
                if (isset($orderActivityArr[$order->id])) {
                    $order->activity = $orderActivityArr[$order->id];
                }
                $list[] = $order;
            }
        }
        $this->back['data'] = $list;
        return $this->back;
    }
    
    /**
     * 订单详情
     * @param Request $request
     * @return type
     */
    public function view(Request $request, $id)
    {
        $data = $this->model->view((int)$id);
        if($data === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '未找到相关订单的信息。';
            return $this->dataToJson($this->back);
        }
        $ids = [$data->id];
        #获取商品详细
        $orderGoodsArr = $this->orderGoodsModel->getOrderGoodsByOrderIds($ids);
        $data->goods = isset($orderGoodsArr[$data->id]) ? $orderGoodsArr[$data->id] : [];
        
        #获取外卖信息
        $takeoutModel = new \App\Models\OrderTakeout();
        $orderTakeoutArr = $takeoutModel->getOrderTakeoutByOrderIds($ids);
        
        #获取优惠活动信息
        $activityModel = new \App\Models\OrderActivity();
        $orderActivityArr = $activityModel->getOrderActivityByOrderIds($ids);
        
        if ($data->is_takeout == 1) {
            $data->takeout = isset($orderTakeoutArr[$data->id]) ? $orderTakeoutArr[$data->id] : new \stdClass();
        }
        if (isset($orderActivityArr[$data->id])) {
            $data->activity = $orderActivityArr[$data->id];
        }
        $data->shops;
        $data->shops->image = asset($data->shops->image);
        $this->back['data'] = $data;
        return $this->back;
    }
    
    
    /**
     * 下订单
     */
    public function created(Request $request)
    {
        $device_type = $request->header('device-type', '');
        $data = $request->all();
        $data['types'] = $data['order_type'];
        $shop = $this->shop->getOne($data['shop_id']);
        //店铺不存在或者 非公开
        if(!$shop || $shop->state != 1) {
            $this->back['status'] = '400';
            $this->back['msg'] = '未找到当前店铺。';
            return $this->dataToJson($this->back);
        }
        
        //判断店铺设置
        $settingModel = app()->make('App\Models\Setting');
        
        $setting = $settingModel->whereExtend(['shop_id' => ['conn' => 'in', 'value' => [$shop->id, 0]]])->get()->pluck('value', 'name');
        
        //餐前支付
        $order_type = ['scan'];
        $data['order_type'] = $setting['postprandial_settlement'] == 1 ? 2 : 1;
        $data['pay_type'] = $setting['postprandial_settlement'] == 1 ? 0 : 2;
        $data['pay_state'] = 0;
        $data['is_takeout'] = 0;
        
        //餐后支付 队列才能下单
        if ($setting['queue_state'] == 1 && $setting['postprandial_settlement'] == 1) {
            $order_type[] = 'queue';
        }
        
        if ($setting['takeout_state'] == 1) {
            $order_type[] = 'takeout';
        }
        
        //餐后支付 预约才能下单
        if ($setting['booking_state'] == 1 && $setting['postprandial_settlement'] == 1) {
            $order_type[] = 'booking';
        }
        
        $valid = [
            'types' => 'in:' . implode(',', $order_type),
            'memo' => 'nullable|max:255',
            'goodsList' => 'required',
            'desk_id' => 'nullable|integer',
            'booking_id' => 'nullable|integer',
            'queue_id' => 'nullable|integer',
            'activity_id' => ['nullable', Rule::exists('activity', 'id')->where(function($query) use($shop) {
                $query->where('shop_id', $shop->id);
            })],
        ];
        
        //外卖配送情况下
        if ($data['types'] == 'takeout') {
            $data['order_type'] = 1;
            $data['is_takeout'] = 1;
            $data['pay_type'] = 2;
         }
         
        //外卖配送情况下地址必填
        if($data['types'] == 'takeout' && $data['takeout_type'] == 1) {
            $valid['takeout_data.accept_name'] = 'required';
            $valid['takeout_data.gender'] = 'in:1,2';
            $valid['takeout_data.phone'] = 'regex:/^1[\d]{10}$/';
            $valid['takeout_data.delivery_time'] = 'date';
            $valid['takeout_data.delivery_address'] = 'required';
            $valid['takeout_data.long'] = ['regex:/^([1-9][\d]?|[1][0-8]\d)(\.[\d]{1,7})?$/'];
            $valid['takeout_data.lat'] = ['regex:/^(|[1-9][\d]?)(\.[\d]{1,7})?$/'];
        }else if($data['types'] == 'takeout' && $data['takeout_type'] == 2) {
            $valid['takeout_data.phone'] = 'regex:/^1[\d]{10}$/';
            $valid['takeout_data.delivery_time'] = 'date';
        }
        
        $msg = [
            'types.in' => '下单类型不存在',
            'memo.max' => '备注情报最多255个字组成。',
            'goodList.required' => '购买商品情报为必填项',
            'desk_id.integer' => '请传入正确的桌号',
            'booking_id.integer' => '请传入正确的预约号',
            'queue_id.integer' => '请传入正确的排队号',
            'activity_id.exists' => '你选择活动不存在',
            'takeout_data.accept_name.required' => '联系人为必填项',
            'takeout_data.gender.in' => '性别输入有误',
            'takeout_data.phone.regex' => '联系电话格式错误',
            'takeout_data.delivery_time.date' => '配送时间格式有误',
            'takeout_data.long.regex' => '经度为必填项',
            'takeout_data.lat.regex' => '纬度为必填项',
        ];
        
        $validator = Validator::make($data, $valid, $msg);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $data['goodsList'] = json_decode($data['goodsList'], true);
        
        if (!empty($data['desk_id'])) {
            //查询座位号
            $desk = $this->desk->whereExtend(['shop_id' => $shop->id, 'id' => $data['desk_id'], 'state' => 1])->first();
            if (!$desk) {
                $this->back['status'] = '400';
                $this->back['msg'] = '桌号找不到，请选择正确的桌号进行点餐。';
                return $this->dataToJson($this->back);
            }
            //设置桌位别名
            $data['desk_alias'] = $desk->alias;
        }
        
        $data['user_id'] = $this->user['id'];
        $data['username'] = $this->user['username'];
        
        $return = $this->model->createOrder($shop, $setting, $this->user, $data, $device_type);
        //save success
        if($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '订单创建失败。';
            return $this->dataToJson($this->back);
        }
        
        $this->back['data'] = $return;
        return $this->back;
    }
    
    /**
     * 餐后支付功能
     * @param Request $request
     * @param Request $id 订单id
     * @return type
     */
    public function payment(Request $request, $id)
    {
        //餐后支付，
        $order = $this->model->whereExtend(['state' => '1', 'id' => intval($id), 'order_type' => '2'])->first();
        
        $activity_id = $request->input('activity_id', 0);
        $device_type = $request->header('device-type');
        
        if (!$order || $this->user['id'] != $order->user_id) {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝访问。';
            return $this->dataToJson($this->back);
        }
        
        $return = $this->model->orderPayment($order, $activity_id, $this->user, $device_type);
        
        if ($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '支付失败。';
            return $this->dataToJson($this->back);
        }
        
        $this->back['data'] = $return;
        return $this->back;
    }
    
    /**
     * 管理者线下收款结束订单
     * @param Request $request
     * @param Request $id 订单id
     * @return type
     */
    public function offlinePay(Request $request, $id)
    {
        //线下收款 设定，
        $order = $this->model->whereExtend(['state' => '1', 'id' => intval($id), 'order_type' => '2'])->first();
        
        $activity_id = $request->input('activity_id', 0);
        $device_type = $request->header('device-type');
        
        //订单不存在或者，不是管理员 没有收银权限
        if (!$order || $this->user['role'] != 1 ||  $this->user['cash_permission'] != '1') {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝操作。';
            return $this->dataToJson($this->back);
        }
        
        $return = $this->model->offlinePay($order, $this->user);
        
        if ($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '设定失败。';
            return $this->dataToJson($this->back);
        }
        
        return $this->back;
    }
    
    
    /**
     * 外卖订单被拒绝
     * @param Request $request
     * @return type
     */
    public function cancel(Request $request)
    {
        $id = $request->input('id', 0);
        
        //已支付的外卖订单
        $order = $this->model->whereExtend(['state' => '2', 'id' => intval($id), 'is_takeout' => '1'])->first();
        
        //订单不存在或者，不是管理员
        if (!$order || $this->user['role'] != 1) {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝操作。';
            return $this->dataToJson($this->back);
        }
        
        $return = $this->model->cancelTakeout($order, $this->user);
        
        if ($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '设定失败。';
            return $this->dataToJson($this->back);
        }
        
        return $this->back;
    }
    /**
     * 外卖接单
     * @param Request $request
     * @return type
     */
    public function receipt(Request $request)
    {
        $id = $request->input('id', 0);
        
        //已支付的外卖订单
        $order = $this->model->whereExtend(['state' => '2', 'id' => intval($id), 'is_takeout' => '1'])->first();
        
        //订单不存在或者，不是管理员
        if (!$order || $this->user['role'] != 1) {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝操作。';
            return $this->dataToJson($this->back);
        }
        
        
        $return = $this->model->receiptTakeout($order, $this->user);
        
        if ($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '设定失败。';
            return $this->dataToJson($this->back);
        }
        
        return $this->back;
    }
    
    
    /**
     * 分配骑手
     * @param Request $request
     * @return type
     */
    public function delivery(Request $request)
    {
        $id = $request->input('id', 0);
        
        //已支付的外卖订单
        $order = $this->model->whereExtend(['state' => '2', 'id' => intval($id), 'is_takeout' => '1'])->first();
        
        //订单不存在或者，不是管理员
        if (!$order || $this->user['role'] != 1) {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝操作。';
            return $this->dataToJson($this->back);
        }
        
        $return = $this->model->deliveryTakeout($order, $this->user);
        
        if ($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '设定失败。';
            return $this->dataToJson($this->back);
        }
        
        return $this->back;
    }
    
    
    /**
     * 配送完成
     * @param Request $request
     * @return type
     */
    public function complete(Request $request)
    {
        $id = $request->input('id', 0);
        
        //已支付的外卖订单
        $order = $this->model->whereExtend(['state' => '2', 'id' => intval($id), 'is_takeout' => '1'])->first();
        
        //订单不存在或者，不是管理员
        if (!$order || $this->user['role'] != 1) {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝操作。';
            return $this->dataToJson($this->back);
        }
        
        $return = $this->model->completeTakeout($order, $this->user);
        
        if ($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '设定失败。';
            return $this->dataToJson($this->back);
        }
        
        return $this->back;
    }
    
    
    /**
     * 上菜状态
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function serving(Request $request, $id)
    {
        $order_goods_id = $request->input('id', 0);
        $state = $request->input('state', 'N');
        //已支付的外卖订单
        $order = $this->model->whereExtend(['state' => '1', 'id' => intval($id), 'is_takeout' => '0'])->first();
        
        //订单不存在或者，不是管理员
        if (!$order || $this->user['role'] != 1) {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝操作。';
            return $this->dataToJson($this->back);
        }
        
        $data = [
            'id' => $order_goods_id,
            'state' => ($state == 'Y' ? 1 : 0)
        ];
        
        $orderGoodsModel = new OrderGoods();
        $return = $orderGoodsModel->saveItem($data);
        
        if ($return === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '操作失败。';
            return $this->dataToJson($this->back);
        }
        
        //推送消息 菜品状态确认
        dispatch(new PushOrderJob($order->id, PushOrderJob::ORDER_GOODS_STATE));
        
        return $this->back;
    }
    
    
    /**
     * 退菜功能
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function refundGoods(Request $request, $id)
    {
        $returnStr = $request->input('return', null);
        //已支付的外卖订单
        $order = $this->model->whereExtend(['state' => '1', 'id' => intval($id), 'is_takeout' => '0'])->first();
        
        //订单不存在或者，不是管理员
        if (!$order || $this->user['role'] != 1) {
            $this->back['status'] = '430';
            $this->back['msg'] = '拒绝操作。';
            return $this->dataToJson($this->back);
        }
        $return = json_decode($returnStr, true);
        if (json_last_error() != JSON_ERROR_NONE || count($return) < 1) {
            $this->back['status'] = '400';
            $this->back['msg'] = '退菜参数错误。';
            return $this->dataToJson($this->back);
        }
        
        $result = $this->model->refundOrderGoods($order, $return);
        
        if ($result === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '退菜操作失败。';
            return $this->dataToJson($this->back);
        }
        
        return $this->back;
    }
    
    
    
    
}
