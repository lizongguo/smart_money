<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\QueueType;
use App\Models\Queue;
use Validator;
use Illuminate\Validation\Rule;
use App\Jobs\SendSmsJob;
use Illuminate\Support\Facades\DB;
use App\Jobs\PushQueueJob;

class QueuesController extends BaseController
{
    protected $model = null;
    protected $tModel = null;

    public function __construct(Request $request, Queue $model, QueueType $tModel)
    {
        $this->model = $model;
        $this->tModel = $tModel;
        parent::__construct($request);
    }
    
    /**
     * 获取队列情报
     * @param Request $request
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'shop_id' => ['required', Rule::exists('shops', 'id')->where(function ($query) {
                $query->where('deleted', 0);
            })],
        ], [
            'shop_id.required' => '店铺ID为必填参数',
            'shop_id.exists' => '店铺不存在或者已删除',
//            'phone.regex' => '电话号码格式有误',
        ]);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $day = date('Y-m-d');
        //查询店铺的队列分类号
        $list = $this->tModel->select('queue_type.id', 'queue_type.shop_id', 'name', 'prefix', 'desc', 'average_time', \DB::raw('count(queue.id) as wait_num'))
            ->leftJoin('queue', function ($join) use($day) {
                $join->on('queue_type_id', '=','queue_type.id')
                    ->where('queue.day', '=', $day)
                    ->where('queue.state', '<', '2');
            })
            ->where('queue_type.shop_id', $data['shop_id'])
            ->where('queue_type.state', 1)
            ->groupBy('queue_type.id')
            ->get();
        
        //没有排队直接返回空数组
        if (count($list) < 1) {
            $this->back['data'] = [];
            return $this->back;
        }
        
        $type_ids = [0];
        foreach ($list as $item) {
            $type_ids[] = $item->id;
        }
        $currents = [];
        $nexts = [];
        $my = [];
        $sort = [];
        
        //查询当前叫号的
        $cList = $this->model->select(
                'queue_type_id',
                DB::raw('max(sort) as sort')
            )
            ->whereIn('queue_type_id', $type_ids)
            ->where('day', $day)
            ->where('state', '2')
            ->groupBy('queue_type_id')
            ->get();
        if (count($cList) > 0) {
            $currentList = $this->model->select(
                'id', 
                'shop_id', 
                'alias', 
                'user_id', 
                'phone', 
                'queue_type_id', 
                'num', 
                'desk_id', 
                'day', 
                'sort',
                'state'
                )
                ->where(function($q) use($cList) {
                    foreach($cList as $item) {
                        $q->orWhere(function($q1) use ($item){
                            $q1->where('queue_type_id', $item->queue_type_id)->where('sort', $item->sort) ;
                        }); 
                    }
                })
                ->where('day', $day)
                ->get();
            foreach($currentList as $item) {
                $currents[$item->queue_type_id] = $item;
            }
        }
        
        
        //查询下一位
        $nList = $this->model->select(
                'queue_type_id',
                DB::raw('min(sort) as sort')
            )
            ->whereIn('queue_type_id', $type_ids)
            ->where('day', $day)
            ->where('state', '<', '2')
            ->groupBy('queue_type_id')
            ->get();
        if (count($nList) > 0) {
            $nextList = $this->model->select(
                'id', 
                'shop_id', 
                'alias', 
                'user_id', 
                'phone', 
                'queue_type_id', 
                'num', 
                'desk_id', 
                'day', 
                'sort',
                'state'
                )
                ->where(function($q) use($nList) {
                    foreach($nList as $item) {
                        $q->orWhere(function($q1) use ($item){
                            $q1->where('queue_type_id', $item->queue_type_id)->where('sort', $item->sort) ;
                        }); 
                    }
                })
                ->where('day', $day)
                ->get();
            foreach($nextList as $item) {
                $nexts[$item->queue_type_id] = $item;
            }
        }
        
        if ($this->user['role'] == 2) { //就餐用户时
            //查询我的队列
            $myQueue = $this->model->select(
                DB::raw('any_value(id) as id'), 
                DB::raw('any_value(shop_id) as shop_id'), 
                DB::raw('any_value(alias) as alias'), 
                DB::raw('any_value(user_id) as user_id'), 
                DB::raw('any_value(phone) as phone'), 
                DB::raw('any_value(queue_type_id) as queue_type_id'), 
                DB::raw('any_value(num) as num'), 
                DB::raw('any_value(desk_id) as desk_id'), 
                DB::raw('any_value(day) as day'), 
                DB::raw('any_value(sort) as sort'), 
                DB::raw('any_value(state) as state'),
                DB::raw('count(id) as num')
                )
                ->whereIn('queue_type_id', $type_ids)
                ->where('day', $day)
                ->where('user_id', $this->user['id'])
                ->whereIn('state', ['1', '0', '3'])
                ->groupBy('queue_type_id')
                ->groupBy('user_id')
                ->get();
            foreach($myQueue as $item) {
                $my[$item->queue_type_id] = $item;
            }
        }
        
        $back = [];
        foreach ($list as $item) {
            $item->currentQueue = isset($currents[$item->id]) ? $currents[$item->id] : new \stdClass;
            $item->nextQueue = isset($nexts[$item->id]) ? $nexts[$item->id] : new \stdClass;
            $item->myQueue = new \stdClass();
            if (isset($my[$item->id])) {
                $my[$item->id]->wait_num = isset($currents[$item->id]) ? ($my[$item->id]->sort - $currents[$item->id]->sort) : $my[$item->id]->sort;
                $my[$item->id]->waiting_minute = $my[$item->id]->wait_num * $item->average_time;
                $item->myQueue = $my[$item->id];
            }
            $back[] = $item;
        }
        
        $this->back['data'] = $back;
        
        return $this->back;
    }
    
    /**
     * 获取具体队列类型队列数据
     * @param Request $request
     * @return type
     */
    public function lists(Request $request) 
    {
        $type_id = $request->input('queue_type_id', 0);
        
        $type = $this->tModel->where('id', intval($type_id))->first();
        
        if (!$type) {
            $this->back['status'] = '400';
            $this->back['msg'] = '队列不存在或者已删除';
            return $this->dataToJson($this->back);
        }
        
        $day = date('Y-m-d');
        
        //查询当前叫号的
        $current = $this->model->select('id', 'shop_id', 'alias', 'user_id', 'phone', 'queue_type_id', 'num', 'desk_id', 'day', 'sort', 'state')
            ->where('queue_type_id', $type->id)
            ->where('day', $day)
            ->orderBy('sort', 'desc')
            ->where('state', '2')
            ->first();
        
        $lists = $this->model->select('id', 'shop_id', 'user_id', 'desk_id', 'phone', 'alias', 'num', 'day', 'sort', 'state', 'created_at')
            ->where('queue_type_id', $type->id)
            ->where('day', $day)
            ->orderBy('sort', 'asc')
            ->whereIn('state', [0, 1, 3])
            ->get();
        
        $currentSort = !!$current ? $current->sort : 0;
        $waitList = [];
        $expireList = [];
        foreach($lists as $item) {
            $item->already_waited_minute = ceil((time() - strtotime($item->created_at)) / 60);
            if ($item->state != 3) {
                $item->waiting_minute = $type->average_time * ($item->sort - $currentSort);
                $waitList[] = $item;
            } else {
                $expireList[] = $item;
            }
        }
        
        $this->back['data'] = [
            'queueList' => $waitList,
            'expireList' => $expireList,
        ];
        
        return $this->back;
    }
    
    
    /**
     * 添加编辑用户预约
     * @param Request $request
     */
    public function created(Request $request) 
    {
        $type_id = $request->input('queue_type_id', 0);
        $phone = $request->input('phone', null);
        $item = $this->tModel->getOne(intval($type_id));
        if (!$item) {
            $this->back['status'] = '400';
            $this->back['msg'] = '队列不存在或者已删除。';
            return $this->dataToJson($this->back);
        }
        
        if (!preg_match('/^1\d{10}$/', $phone)) {
            $this->back['status'] = '400';
            $this->back['msg'] = '电话号码格式错误。';
            return $this->dataToJson($this->back);
        }
        
        //查询改用户当天时候存在未使用的队列
        $queue = $this->model
            ->where('user_id', $this->user['id'])
            ->where('shop_id', $item->shop_id)
            ->where('day', date('Y-m-d'))
            ->whereIn('state', ['0', '1', '3'])
            ->first();
        
        if (!!$queue) {
            $this->back['status'] = '430';
            $this->back['msg'] = '你还存在未就餐的队列，不能再创建队列。';
            return $this->dataToJson($this->back);
        }
        
        $id = $this->model->createdQueue($type_id, $phone, $this->user['id'], $item);
        if ($id === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误。';
            return $this->dataToJson($this->back);
        }
        
        //推送消息
        dispatch(new PushQueueJob($id, PushQueueJob::CREATE));
        
        //获取预约情报
        $this->back['data'] = $this->model->getOne($id);
        return $this->back;
    }
    
    /**
     * 取消排队
     * @param Request $request
     */
    public function cancel(Request $request)
    {
        $id = $request->input('queue_id', 0);
        $item = $this->model->getOne(intval($id));
        if (!$item || $item->user_id != $this->user['id']) {
            $this->back['status'] = '400';
            $this->back['msg'] = '该排队未找到。';
            return $this->dataToJson($this->back);
        }
        
        //就等待状态方可取消
        if ($item->state != 0) {
            $this->back['status'] = '400';
            $this->back['msg'] = '当前状态无法取消。';
            return $this->dataToJson($this->back);
        }
        
        //取消
        $rs = $this->model->cancelQueue($id, $item);
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误。';
            return $this->dataToJson($this->back);
        }
        //推送消息
        dispatch(new PushQueueJob($id, PushQueueJob::CANCEL));
        
        return $this->back;
    }
    
    /**
     * 过号设置
     * @param Request $request
     */
    public function expire(Request $request)
    {
        $id = $request->input('queue_id', 0);
        $item = $this->model->getOne(intval($id));
        if (!$item) {
            $this->back['status'] = '400';
            $this->back['msg'] = '该排队未找到。';
            return $this->dataToJson($this->back);
        }
        
        //就等待状态方可 过号, 切用户必须为管理员
        if ($item->state > 1 || $this->user['role'] != 1) {
            $this->back['status'] = '400';
            $this->back['msg'] = '当前状态无法过号，或者你不是管理员。';
            return $this->dataToJson($this->back);
        }
        
        $rs = $this->model->expireQueue($id, $item);
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误。';
            return $this->dataToJson($this->back);
        }
        //推送消息
        dispatch(new PushQueueJob($id, PushQueueJob::EXPIRE));
        
        return $this->back;
    }
    
    
    /**
     * 插队模块
     * @param Request $request
     */
    public function jumping(Request $request)
    {
        $id = $request->input('queue_id', 0);
        $item = $this->model->getOne(intval($id));
        if (!$item) {
            $this->back['status'] = '400';
            $this->back['msg'] = '该排队未找到。';
            return $this->dataToJson($this->back);
        }
        
        //就等待状态方可 过号, 切用户必须为管理员
        if ($item->state != 3 || $this->user['role'] != 1) {
            $this->back['status'] = '400';
            $this->back['msg'] = '当前状态无法插队，或者你不是管理员。';
            return $this->dataToJson($this->back);
        }
        
        $rs = $this->model->jumpingQueue($id, $item);
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误。';
            return $this->dataToJson($this->back);
        }
        
        //推送消息
        dispatch(new PushQueueJob($id, PushQueueJob::JUMPING));
        
        return $this->back;
    }
    
    
    /**
     * 就餐功能
     * @param Request $request
     * @return type
     */
    public function eat(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'queue_id' => ['numeric'],
            'desk_id' => ['numeric'],
        ], [
            'id.numeric' => '排队不存在',
            'desk_id.numeric' => '桌位输入有误',
            'desk_id.exists' => '桌位不存在',
        ]);
        
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $id = $this->model->eat($data['queue_id'], $data['desk_id']);
        if ($id === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '排队就餐设定失败。';
            return $this->dataToJson($this->back);
        }
        
        //推送消息
        dispatch(new PushQueueJob($data['queue_id'], PushQueueJob::EAT));
        
        //获取预约情报
//        $this->back['data'] = $this->model->getOne($id);
        return $this->back;
    }
    
    /**
     * 叫号功能
     * @param Request $request
     * @return type
     */
    public function callNumber(Request $request)
    {
        $id = $request->input('queue_id', 0);
        $item = $this->model->getOne(intval($id));
        if (!$item) {
            $this->back['status'] = '400';
            $this->back['msg'] = '该排队未找到。';
            return $this->dataToJson($this->back);
        }
        
        //就等待状态方可 过号, 切用户必须为管理员
        if (!in_array($item->state, [0, 1]) || $this->user['role'] != 1 || $this->user['queue_permission'] != 1) {
            $this->back['status'] = '400';
            $this->back['msg'] = '当前状态无法叫号，或者你不是管理员。';
            return $this->dataToJson($this->back);
        }
        
        $rs = $this->model->callNumberQueue($id, $item);
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误。';
            return $this->dataToJson($this->back);
        }
        
        return $this->back;
    }
    
    
    /**
     * 统计数量
     * @param Request $request
     * @return type
     */
    public function statistics(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'shop_id' => ['required', Rule::exists('shops', 'id')->where(function ($query) {
                $query->where('deleted', 0);
            })],
        ], [
            'shop_id.required' => '店铺ID为必填参数',
            'shop_id.exists' => '店铺不存在或者已删除',
//            'phone.regex' => '电话号码格式有误',
        ]);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        if ($this->user['role'] != 1) {
            $this->back['status'] = '400';
            $this->back['msg'] = "您无权访问当前数据。";
            return $this->dataToJson($this->back);
        }
        
        $day = date('Y-m-d');
        //获取预约数
        $queue_num = $this->model
            ->where('shop_id', $data['shop_id'])
            ->where('day', $day)
            ->whereIn('state', ['0', '1', '3'])
            ->count();
        
        //获取预约数
        $bookingModel = new \App\Models\Booking();
        $booking_num = $bookingModel->where('shop_id', $data['shop_id'])
            ->where('booking_time', '>', $day . ' 00:00:00')
            ->where('state', '<', 2)
            ->count();
        
        $this->back['data'] = [
            'queue_num' => $queue_num,
            'booking_num' => $booking_num,
        ];
        return $this->back;
    }
    
}