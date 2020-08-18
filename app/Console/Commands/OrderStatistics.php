<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\Statistics;
use App\Libraries\BLogger;
use Cache;

class OrderStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'statistics:order';

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
     *
     * @var App\Http\Models\Statistics
     */
    protected $statistics = null;
    
    
    protected $preDayTime = null;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Orders $order, Statistics $statistics)
    {
        parent::__construct();
        $this->order = $order;
        $this->statistics = $statistics;
        
        $this->preDayTime = strtotime('-1 day');
    }

    /**
     * 
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('refresh token batch is start!');
        
        $rs = $this->runSchedule();
        if ($rs === true) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('refresh token batch is complate!');
        } else {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('refresh token batch is failed!');
        }
    }
    
    protected function runSchedule() {
        if(!$this->checkCurrentDayIsRun()) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info(date('Y-m-d', $this->preDayTime) . "日结统计单已生成。");
            return false;
        }
        $sDate = date('Y-m-d 00:00:00', $this->preDayTime);
        $eDate = date('Y-m-d 23:59:59', $this->preDayTime);
        $orders = $this->order
            ->select('shop_id', 'pay_type', \DB::raw('sum(payment_amount) as total'))
            ->whereBetween('created_at', [$sDate, $eDate])
            ->where('state', 99)
            ->whereIn('pay_type', [1, 2])
            ->where('deleted', 0)
            ->groupBy('shop_id', 'pay_type')
            ->get();
        $data = [];
        $day = date('Y-m-d', $this->preDayTime);
        $month = date('Ym', $this->preDayTime);
        foreach($orders as $totals) {
            $field = 'offline_amount';
            if ($totals->pay_type == 2) {
                $field = 'online_amount';
            }
            $data[$totals->shop_id][$field] = $totals->total;
            if (isset($data[$totals->shop_id]['total_amount'])) {
                $data[$totals->shop_id]['total_amount'] +=  $totals->total;
            } else {
                $data[$totals->shop_id]['total_amount'] =  $totals->total;
            }
        }
        $insert = [];
        $now = date('Y-m-d H:i:s');
        foreach($data as $shop_id => $item) {
            $insert[] = [
                'shop_id' => $shop_id,
                'day' => $day,
                'month' => $month,
                'online_amount' => isset($item['online_amount']) ? $item['online_amount'] : 0,
                'offline_amount' => isset($item['offline_amount']) ? $item['offline_amount'] : 0,
                'total_amount' => isset($item['total_amount']) ? $item['total_amount'] : 0,
                'state' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted' => 0
            ];
        }
        $rs = true;
        if (count($insert) > 0) {
            $rs = $this->statistics->insertBatch($insert);
        }
        if ($rs !== false) {
            //保存最后一次生成的清单月份到cache中，方便下次验证是否已经生成。
            $this->saveCurrentDayToCache();
        }
        return $rs;
    }
    
    protected function checkCurrentDayIsRun() {
        if(!Cache::has('statistics_day')) {
            return true;
        }
        $oldMonth = Cache::get('statistics_day');
        if($oldMonth >= date('Ymd', $this->preDayTime)) {
            return false;
        }
        return true;
    }
    
    /**
     * 保存当前处理日期到cache
     * @return type
     */
    protected function saveCurrentDayToCache() {
        return Cache::forever('statistics_day', date('Ymd', $this->preDayTime));
    }
}
