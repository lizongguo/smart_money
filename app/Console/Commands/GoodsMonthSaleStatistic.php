<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderGoods;
use App\Models\Goods;
use App\Libraries\BLogger;

class GoodsMonthSaleStatistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'statistic:monthSale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计商品月销金额';
    
    /**
     *
     * @var App\Models\OrderGoods
     */
    protected $order = null;
    
    /**
     *
     * @var App\Http\Models\Goods
     */
    protected $goods = null;
    
    
    protected $firstId = null;
    /**
     * Create a new command instance.
     *
     * @return voidj7
     */
    public function __construct(OrderGoods $order, Goods $goods)
    {
        parent::__construct();
        $this->order = $order;
        $this->goods = $goods;
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
        BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('mouth sale statistic batch is start!');
        
        $rs = $this->runSchedule();
        if ($rs === true) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('mouth sale statistic is complate!');
        } else {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('mouth sale statistic is failed!');
        }
    }
    
    protected function runSchedule() {
        //查询商品分页
        $limit = 100;
        $this->firstId = 0;
        $this->sDate = date('Y-m-d 00:00:00', strtotime('-30 day'));
        $this->eDate = date('Y-m-d 00:00:00');
        do {
            $goodsList = $this->goods->where('is_shelves', '1')
                ->where('deleted', 0)
                ->where('id', '>', $this->firstId)
                ->limit($limit)->pluck('id');
            
            $this->dealGoodsSale($goodsList);
            
            if (count($goodsList) < $limit) {
                break;
            }
        }while (true);
        return true;
    }
    
    public function dealGoodsSale($goodsList) {
        if (count($goodsList) < 1) {
            return false;
        }
        $sales = $this->order
            ->select('goods_id', \DB::raw('sum(order_goods.goods_num) as num'))
            ->join('orders', 'orders.id', '=', 'order_goods.order_id')
            ->whereBetween('tb_order.created_at', [$this->sDate, $this->eDate])
            ->whereIn('goods_id', $goodsList)
            ->where('orders.state', 99)
            ->where('orders.deleted', 0)
            ->groupBy('goods_id')
            ->pluck('num', 'goods_id');
        
        foreach($sales as $goods_id => $num) {
            $rs = $this->goods->where('id', $goods_id)->update(['sale' => $num]);
            if ($rs === false) {
                BLogger::getLogger(BLogger::LOG_SCHEDULE)
                    ->info("monthSale goods:{$goods_id} sale:{$num} is save failed.");
            }
        }
    }
}
