<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orders;
use App\Libraries\BLogger;
use App\Services\Order\OrderRefundService;

class OrderRefund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'refund:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计店铺每天的运业金额';
    
    /**
     *
     * @var App\Models\Order
     */
    protected $order = null;
    
    /**
     * @var App\Services\Order\OrderRefundService 
     */
    protected $refundService = null;
    
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Orders $order, OrderRefundService $refundService)
    {
        parent::__construct();
        $this->order = $order;
        $this->refundService = $refundService;
    }

    /**
     * 
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('refund  batch is start!');
        
        $rs = $this->runRefund();
        if ($rs === true) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('refund batch is complate!');
        } else {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('refund batch is failed!');
        }
    }
    
    protected function runRefund()
    {
        $orders = $this->order->select('id')
            ->where('state', 96)
            ->where('deleted', 0)
            ->get();
        
        foreach($orders as $order) {
            $this->refundService->refundOrderPay($order->id);
        }
        
    }
}
