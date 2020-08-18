<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderTakeout;
use App\Libraries\BLogger;
use App\Services\Dada\DadaPeisong;

class OrderPeisong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'peisong:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单配送自动推单';
    
    /**
     *
     * @var App\Models\OrderTakeout
     */
    protected $takeout = null;
    
    /**
     * @var App\Services\Meituan\PeisongService 
     */
    protected $peisongService = null;
    
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderTakeout $takeout, DadaPeisong $peisongService)
    {
        parent::__construct();
        $this->takeout = $takeout;
        $this->peisongService = $peisongService;
    }

    /**
     * 
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('peisong  batch is start!');
        
        $rs = $this->runPeisong();
        if ($rs === true) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('peisong batch is complate!');
        } else {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('peisong batch is failed!');
        }
    }
    
    protected function runPeisong()
    {
        $takeouts = $this->takeout->select('order_id')
            ->where('takeout_type', 1)
            ->where('take_cate', '2')
            ->where('takeout_state', 1)
            ->where('takeout_push_state', 0)
            ->get();
        
        foreach($takeouts as $takeout) {
            //添加推送第三方配送 推单队列
//            dispatch();
            $job = new \App\Jobs\PeisongJob($takeout->order_id, \App\Jobs\PeisongJob::PEISONG_CREATE);
            $rs = $job->handle();
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info("订单id：{$takeout->order_id}, 配送订单创建, 操作" . ($rs ? "成功。" : "失败。"));
        }
        return true;
    }
}
