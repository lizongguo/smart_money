<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushQueueJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $queue_id;
    protected $event;
    
    /**
     * 推送事件
     */
    const CREATE = 'QUEUE_CREATE'; //创建队列
    const CANCEL = 'QUEUE_CANCEL'; //取消队列
    const JUMPING  = 'QUEUE_JUMPING'; //插队
    const EXPIRE= 'QUEUE_EXPIRE'; //过号
    const EAT = 'QUEUE_EAT'; //就餐
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($queue_id, $event = self::CREATE)
    {
        $this->queue_id = $queue_id;
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $queuePush = app()->make('App\Services\Order\QueuePushService');
        //推送订单push
        $queuePush->sendPush($this->queue_id, $this->event);
    }
}
