<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Jobs\SendSmsJob;
use DB;
use Illuminate\Support\Facades\Redis;

class Queue extends BaseModel
{
    protected $table = 'queue';
    protected $primaryKey = 'id';
    protected $isDeleted = false;


    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function shops() : BelongsTo
    {
        return $this->belongsTo(Shops::class, 'shop_id');
    }
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function types() : BelongsTo
    {
        return $this->belongsTo(QueueType::class, 'queue_type_id');
    }
    
    /**
     * 获取队列号自增号
     * @param type $type_id
     * @return int
     */
    public function getQueueByTypeId($type_id) 
    {
        $redisKey = sprintf(config('rediskeys.queue_type_no'), $type_id);
        //如果存在，自增+1
        if(Redis::exists($redisKey)) {
            return Redis::incr($redisKey);
        } else {
            Redis::set($redisKey, 1);
            //设置今日23点59分59秒过期
            Redis::expireat($redisKey, strtotime(date('Y-m-d 23:59:59')));
            return 1;
        }
    }
    
    public function getMaxSort($type_id, $day)
    {
        $max_sort = $this->where('queue_type_id', $type_id)->where('day', $day)->select(DB::raw('max(sort) as num'))->first();
        return $max_sort->num;
    }
    
    
    public function createdQueue($type_id, $phone, $user_id, $type = null)
    {
        if (empty($type)) {
            $typeModel = new QueueType;
            $type =  $typeModel->getOne($type_id);
        }
        $day = date('Y-m-d');
        $max_sort = $this->getMaxSort($type->id, $day);
        $sort = $max_sort + 1;
        $num = $this->getQueueByTypeId($type->id);
        
        $save = [
            'shop_id' => $type->shop_id,
            'user_id' => $user_id,
            'phone' => $phone,
            'queue_type_id' => $type->id,
            'alias' => $type->prefix . $num,
            'sort' => $sort,
            'num' => $num,
            'desk_id' => 0,
            'day' => $day,
            'state' => 0
        ];
        
        $id = $this->saveItem($save);
        return $id;
    }
    
    
    public function cancelQueue($queue_id, $queue = null)
    {
        if (empty($queue)) {
            $queue =  $this->getOne($queue_id);
        }
        $data = [
            'id' => $queue->id,
            'state' => 4,
            'sort' => '0',
        ];
        \DB::beginTransaction();
        try {
            $id = $this->saveItem($data);
            //修改后序排队的顺序号自动减 1
            $this->where('queue_type_id', $queue->queue_type_id)->where('day', $queue->day)->where('sort', '>', $queue->sort)->decrement('sort', 1);
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return $id;
    }
    
    
    public function expireQueue($queue_id, $queue = null)
    {
        if (empty($queue)) {
            $queue =  $this->getOne($queue_id);
        }
        $data = [
            'id' => $queue->id,
            'state' => 3,
            'sort' => '0',
        ];
        \DB::beginTransaction();
        try {
            $id = $this->saveItem($data);
            //修改后序排队的顺序号自动减 1
            $this->where('queue_type_id', $queue->queue_type_id)->where('day', $queue->day)->where('sort', '>', $queue->sort)->decrement('sort', 1);
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        
        //通知短信
        $settingModel = app()->make("App\Models\Setting");
        $setting = $settingModel->getSettingByCategory('排号设置');
        $num = $setting['queue_sms_number'] ? $setting['queue_sms_number'] : 0;
        if ($num > 0) {
            $item = $this->select('id', 'phone')
                ->where('queue_type_id', $queue->queue_type_id)
                ->where('day', $queue->day)
                ->where('sort', $queue->sort + $num + 1)
                ->first();
            if (!!$item) {
                $params = [
                    'num' => $num - 1
                ];
                dispatch(new SendSmsJob($item->phone, $params, SendSmsJob::QUEUE_WAIT_NUM));
            }
        }
        
        return $id;
    }
    
    /**
     * 插队功能
     * @param type $queue_id
     * @param type $queue
     * @return boolean
     */
    public function jumpingQueue($queue_id, $queue = null)
    {
        if (empty($queue)) {
            $queue =  $this->getOne($queue_id);
        }
        
        //查询设置
        $settingModel = new Setting();
        $set = $settingModel->getShopSettingByCategory($queue->shop_id, '排号设置');
        $day = date('Y-m-d');
        $current = $this->select('sort')
            ->where('queue_type_id', $queue->queue_type_id)
            ->where('day', $day)
            ->orderBy('sort', 'desc')
            ->where('state', '2')
            ->first();
        $max_sort = $this->getMaxSort($queue->queue_type_id, $day);
        
        $sort = $current->sort + 1 + $set['queue_postpone_number'];
        if ($max_sort < $sort) {
            $sort = $max_sort + 1;
        }
        $data = [
            'id' => $queue->id,
            'state' => 0,
            'sort' => $sort,
        ];
        \DB::beginTransaction();
        try {
            //修改后序排队的顺序号自动加 1
            $this->where('queue_type_id', $queue->queue_type_id)->where('day', $queue->day)->where('sort', '>=', $sort)->increment('sort', 1);
            $id = $this->saveItem($data);
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return $id;
    }
    
    /**
     * 排队就餐设置
     * @param type $id
     * @param type $desk_id
     * @return boolean
     */
    public function eat($id, $desk_id)
    {
        $queue = $this->getOne($id);
        $desk = (new Desks())->getOne($desk_id);
        if (!$queue || !$desk) {
            return false;
        }
        
        $data = [
            'id' => $queue->id,
            'state' => 2,
            'desk_id' => $desk_id,
        ];
        \DB::beginTransaction();
        try {
            $rs = $this->saveItem($data);
            //修改提前点餐的订单绑定桌位号
            $orderModel = new Orders();
            $orderModel->where('queue_id', $queue->id)->update([
                'desk_id' => $desk->id,
                'desk_alias' => $desk->alias
            ]);
            //todo...
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        
        //通知短信
        $settingModel = app()->make("App\Models\Setting");
        $setting = $settingModel->getSettingByCategory('排号设置');
        $num = $setting['queue_sms_number'] ? $setting['queue_sms_number'] : 0;
        if ($num > 0) {
            $item = $this->select('id', 'phone')
                ->where('queue_type_id', $queue->queue_type_id)
                ->where('day', $queue->day)
                ->where('sort', $queue->sort + $num)
                ->first();
            if (!!$item) {
                $params = [
                    'num' => $num - 1
                ];
                dispatch(new SendSmsJob($item->phone, $params, SendSmsJob::QUEUE_WAIT_NUM));
            }
        }
        
        return $rs;
    }
    
    /**
     * 叫号操作
     * 
     * @param type $queue_id
     * @param type $queue
     * @return boolean
     */
    public function callNumberQueue($queue_id, $queue = null)
    {
        if (empty($queue)) {
            $queue =  $this->getOne($queue_id);
        }
        
        $data = [
            'id' => $queue->id,
            'state' => 1,
        ];
        $id = $this->saveItem($data);
        
        return $id;
    }
}
