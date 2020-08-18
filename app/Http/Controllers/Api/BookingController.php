<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Booking;
use Validator;
use App\Jobs\SendSmsJob;
use Illuminate\Validation\Rule;
use App\Jobs\PushOrderJob;

class BookingController extends BaseController
{
    protected $model = null;

    public function __construct(Request $request, Booking $model)
    {
        $this->model = $model;
        parent::__construct($request);
    }
    
    /**
     * 获取用户预约
     * @param Request $request
     */
    public function index(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'shop_id' => ['required', Rule::exists('shops', 'id')->where(function ($query) {
                $query->where('deleted', 0);
            })],
            'phone' => 'nullable|regex:/^1[\d]{10}^/',
        ], [
            'shop_id.required' => '店铺ID为必填参数',
            'shop_id.exists' => '店铺不存在或者已删除',
            'phone.regex' => '电话号码格式有误',
        ]);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        //处理查询条件
        $condition = ['booking.shop_id' => $data['shop_id']];
        if (!!$data['phone']) {
            $condition['phone'] = $data['phone'];
        }
        $condition['booking_time'] = ['conn' => '>=', 'value' => date('Y-m-d 00:00:00')];
        $condition['booking.state'] = ['conn' => '!=', 'value' => '3'];
        
        $bookings = $this->model->whereExtend($condition)
            ->select('booking.id', 'booking.user_id', 'booking.shop_id', 'number', 'telphone', \DB::raw('booking.desk_ids as desk_id'),
                'booking_time', 'booking.state', 'booking_no', 'remark', \DB::raw('orders.id as order_id'), \DB::raw('orders.state as order_state'))
            ->orderBy('booking_time', 'asc')
            ->leftJoin('orders', function ($join) {
                $join->on('booking_id', '=','booking.id')->where('orders.deleted', '=', '0');
            })
            ->get();
        
        $after = [];
        $today = [];
        $now = date('Y-m-d 23:59:59');
        foreach($bookings as $booking) {
            if (empty($booking->order_id)) {
                $booking->order_id = 0;
            }
            if (empty($booking->order_state)) {
                $booking->order_state = 0;
            }
            if ($booking->booking_time <= $now) {
                $today[] = $booking;
            } else {
                $after[] = $booking;
            }
        }

        $this->back['data'] = [
            'today' => $today,
            'after' => $after,
        ];
        
        return $this->back;
    }
    
    /**
     * 添加编辑用户预约
     * @param Request $request
     */
    public function created(Request $request, $id = 0) {
        $data = $request->all();
        $item = $this->model->getOne($id);
        if (!!$item) {
            if ($item['user_id'] != $this->user['id']) {
                $this->back['status'] = '400';
                $this->back['msg'] = '拒绝访问。';
                return $this->dataToJson($this->back);
            }
            $data['id'] = $item['id'];
        }
        
        $data['user_id'] = $this->user['id'];
        
        $validator = Validator::make($data, [
            'booking_time' => 'date',
            'number' => 'numeric',
            'telphone' => 'regex:/^1[\d]{10}$/'
        ], [
            'booking_time.date' => '预约时间输入有误',
            'number.numeric' => '预约人数只能是数字',
            'telphone.regex' => '联系电话格式有误',
        ]);
        
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $rs = $this->model->saveItem($data);
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误。';
            return $this->dataToJson($this->back);
        }
        
        //创建push推送
        dispatch(new PushOrderJob($rs, PushOrderJob::BOOKING_CREATE));
        
        //获取预约情报
        $this->back['data'] = $this->model->getOne($rs);
        return $this->back;
    }
    
    /**
     * 预约状态变更
     * @param Request $request
     */
    public function confirm(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => [
                'numeric',
//                'exists:booking,id',
//                Rule::exists('booking', 'id')->where(function ($query) {
//                    $query->where('user_id', $this->user['id']);
//                })
            ],
            'state' => 'in:Y,N,C'
        ], [
            'id.numeric' => '预约不存在',
            'booking_id.exists' => '预约不存在',
            'state.in' => '状态参数输入错误',
        ]);
        $booking = $this->model->getOne($data['id']);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        $stateMap = [
            'Y' => 1,
            'N' => 4,
            'C' => 3
        ];
        $save = [
            'id' => $booking->id,
            'state' => $stateMap[$data['state']]
        ];
        
        if (!$booking) {
            $this->back['status'] = '400';
            $this->back['msg'] = "预约订单不存在。";
            return $this->dataToJson($this->back);
        }
        
        if ($this->user['role'] == 2 && ($this->user['id'] != $booking->user_id || $data['state'] != 'C')) {
            $this->back['status'] = '400';
            $this->back['msg'] = "你没有权限操作。";
            return $this->dataToJson($this->back);
        }else if ($this->user['role'] == 1 &&  $data['state'] == 'C'){
            $this->back['status'] = '400';
            $this->back['msg'] = "你没有权限操作。";
            return $this->dataToJson($this->back);
        }
        
        
        $rs = $this->model->saveItem($save);
        
        
        //处理短信问题
        $params = [
            'shop_name' => $booking->shops->shop_name,
            'booking_time' => $booking->booking_time
        ];
        if ($data['state'] == 'N') {
            $params['phone'] = $booking->shops->phone;
        }
        if ($data['state'] == 'N' || $data['state'] == 'Y') {
            $type = $data['state'] == 'Y' ? SendSmsJob::BOOKING_OK : SendSmsJob::BOOKING_CANCEL;
            dispatch(new SendSmsJob($booking->telphone, $params, $type));
        }
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '状态变更失败。';
            return $this->dataToJson($this->back);
        }
        //消息推送
        dispatch(new PushOrderJob($booking->id, $data['state'] == 'Y' ? PushOrderJob::BOOKING_OK : ($data['state'] == 'N' ? PushOrderJob::BOOKING_NO : PushOrderJob::BOOKING_CANCEL)));
        
        //获取预约情报
//        $this->back['data'] = $this->model->getOne($rs);
        return $this->back;
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function eat(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => ['numeric', 'exists:booking,id'],
            'desk_id' => ['numeric', 'exists:desks,id'],
        ], [
            'id.numeric' => '预约不存在',
            'id.exists' => '预约不存在',
            'desk_id.numeric' => '桌位输入有误',
            'desk_id.exists' => '桌位不存在',
        ]);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $id = $this->model->eat($data['id'], $data['desk_id']);
        if ($id === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '就餐桌位绑定失败。';
            return $this->dataToJson($this->back);
        }
        
        //消息推送
        dispatch(new PushOrderJob($data['id'], PushOrderJob::BOOKING_EAT));
        
        //获取预约情报
//        $this->back['data'] = $this->model->getOne($id);
        return $this->back;
    }
    
    
    /**
     * 删除我的预约
     * @param type $id 预约id
     * @return type
     */
    public function deleted($id) {
        $item = $this->model->getOne(intval($id));
        
        if (!$item || $item->user_id != $this->user['id']) {
            $this->back['status'] = '400';
            $this->back['msg'] = '拒绝访问。';
            return $this->dataToJson($this->back);
        }
        
        $rs = $item->delete();
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '地址删除失败。';
            return $this->dataToJson($this->back);
        }
        return $this->back;
    }
    
    /**
     * 我的预约
     * @param Request $request
     * @return type
     */
    public function mybooking(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'shop_id' => ['nullable', Rule::exists('shops', 'id')->where(function ($query) {
                $query->where('deleted', 0);
            })]
        ], [
            'shop_id.exists' => '店铺不存在或者已删除'
        ]);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        //处理查询条件
        $condition = ['booking.user_id' => $this->user['id']];
        if (!!$data['shop_id']) {
            $condition['booking.shop_id'] = $data['shop_id'];
        }
        
        $bookings = $this->model->whereExtend($condition)
            ->select('booking.id', 'booking.user_id', 'booking.shop_id', 'number', 'telphone', \DB::raw('booking.desk_ids as desk_id'),
                'booking_time', 'booking.state', 'booking_no', 'remark', \DB::raw('orders.id as order_id'), \DB::raw('orders.state as order_state'))
            ->orderBy('state', 'asc')
            ->orderBy('booking_time', 'asc')
            ->leftJoin('orders', function ($join) {
                $join->on('booking_id', '=','booking.id')->where('orders.deleted', '=', '0');
            })
            ->get();
        
        foreach($bookings as &$booking) {
            if (empty($booking->order_id)) {
                $booking->order_id = 0;
            }
            if (empty($booking->order_state)) {
                $booking->order_state = 0;
            }
            $booking->shops;
        }
        $this->back['data'] = $bookings;
        return $this->back;
    }
}