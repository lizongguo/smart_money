<?php
namespace App\Services\Order;

//use App\Services\Jpush\Jpush;
use App\Services\Aliyun\Alipush;
use App\Models\Waiter;
use App\Libraries\BLogger;
use App\Jobs\PushQueueJob;
use App\Models\Queue;

/**
 * OrderPush
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-12-10 12:12:55
 * @copyright   Copyright(C) kbftech Inc.
 */
class QueuePushService {
    protected $pusher = null;
    protected $waiter = null;
    protected $queue = null;
    protected $pusherType = 'aliyun';

    public function __construct(Alipush $pusher, Waiter $waiter, Queue $queue) {
        $this->pusher = $pusher;
        $this->waiter = $waiter;
        $this->queue = $queue;
    }
    
    /**
     * 发送push推送
     * 
     * @param type $queue_id
     * @return boolean
     */
    public function sendPush($queue_id,  $event = PushQueueJob::CREATE)
    {
        $queue = $this->queue->getOne($queue_id);
        if (!$queue) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error("队列id【{$queue_id}】,不存在");
            return false;
        }
        
        //查询店铺员工厨师的用户
        $cookUser = $this->waiter->getUserByShopId($queue->shop_id);
        $tokens = [];
        $tokenArr = [];
        foreach($cookUser as $user) {
            if (empty($user->push_token)) {
                continue;
            }
            $tokens[] = $user->push_token;
            $tokenArr[$user->device_type][] = $user->push_token;
        }
        
        //token不存在，直接返回
        if(count($tokens) < 1) {
            return true;
        }
        
        $extras = [
            'queue_id' => $queue->id, //队列id
            'alias' => $queue->alias, //队列号
            'queue_type_id' => $queue->queue_type_id, //队列分组
            'state' => $queue->state, //队列状态
            'event' => $event //事件
        ];
        
        $type = 'alert';
        //处理消息体
        switch ($event) {
            case PushQueueJob::CREATE :
                //发送push通知
                $message = "您收到一个新排队，队列号【{$queue->alias}】。";
                $title = "您收到一个新排队通知";
                break;
            
            case PushQueueJob::CANCEL :
                //发送push通知
                $message = "您收到一个取消排队，队列号【{$queue->alias}】。";
                $title = "您收到一个取消排队通知";
                break;
            case PushQueueJob::JUMPING :
                //发送push通知
                $message = "您收到一个插队排队，队列号【{$queue->alias}】。";
                $title = "您收到一个插队排队通知";
                break;
            case PushQueueJob::EXPIRE :
                //发送push通知
                $message = "您收到一个过号排队，队列号【{$queue->alias}】。";
                $title = "您收到一个过号排队通知";
                break;
            
            case PushQueueJob::EAT :
                //发送push通知
                $message = "您收到一个就餐排队，队列号【{$queue->alias}】。";
                $title = "您收到一个就餐排队通知";
                break;
            default:
                return false;
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
                $msg = "queue_id: {$queue_id}, {$device_type} 推送发送" . (($rs === false) ? "失败。" : "成功。");
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
        
        $msg = "queue_id: {$queue_id}, 推送发送" . (($result === false) ? "失败。" : "成功。");
        if ($result === false) {
            BLogger::getLogger(BLogger::LOG_PUSH)->error($msg);
            return false;
        }
        BLogger::getLogger(BLogger::LOG_PUSH)->info($msg);
        return true;
    }
    
}
