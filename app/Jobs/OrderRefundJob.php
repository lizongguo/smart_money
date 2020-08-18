<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRefundJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    protected $order_id;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $refundService = app()->make('App\Services\Order\OrderRefundService');
        //推送订单push
        $refundService->refundOrderPay($this->order_id);
    }
}
