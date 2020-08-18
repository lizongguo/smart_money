<?php

/**
 * GetCouchbaseRepository
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-4-16 17:05:16
 * @copyright   Copyright(C) bravesoft Inc.
 */
namespace App\Services\Settlement;
use App\Models\Orders;
use App\Models\OrderSettlement;
use App\Models\Settlement as SettlementModel;
use Cache;
use DB;

class SettlementService implements Settlement
{
    protected $orderModel = null;
    protected $orderSettlement = null;
    protected $settlementModel = null;
    protected $settlement_id = null;
    protected $pageNum = 100;
    /**
     * 前一天的时间戳
     * @var int 
     */
    protected $preDayTime = null; 
  
    public function __construct(Orders $order, SettlementModel $settlement, OrderSettlement $orderSettlement)
    {
        $this->orderModel = $order;
        $this->settlementModel = $settlement;
        $this->orderSettlement = $orderSettlement;
        $this->preDayTime = strtotime('-1 day', strtotime(date('Y-m-d')));
    }
    
    public function runService() {
        if(!$this->checkCurrentDayIsRun()) {
            echo date('Y-m-d', $this->preDayTime) . "日结算清单已生成。";
            return false;
        }
        
        DB::beginTransaction();
        $rs = $this->createSettlement();
        if($rs === false) {
            DB::rollBack();
            return false;
        }
        
        do {
            $sh = [
                'is_settlement' => 0,
                'state' => 99,
                'deleted' => 0,
                'complete_time' => ['conn' => '<=', 'value' => date('Y-m-d', $this->preDayTime) . " 23:59:59"]
            ];
            
            //查询订单数据
            $orders = $this->orderModel->getList($sh, false, $this->pageNum);
            //处理订单数据
            $rs = $this->dealOrders($orders);
            
            if($rs === false) {
                DB::rollBack();
                return false;
            }
            
            if(count($orders) < $this->pageNum) {
                break;
            }
        }while (true);
        DB::commit();
        //保存最后一次生成的清单月份到cache中，方便下次验证是否已经生成。
        $this->saveCurrentDayToCache();
        
        return true;
    }
    
    /**
     * 计算结算金额
     * @param type $amount
     * @param type $rate
     */
    protected function amountCalculation($amount, $rate) {
        return ($amount * $rate) / 100;
    }
    
    /**
     *  处理订单情报
     * @param type $orders
     * @return boolean
     */
    protected function dealOrders($orders) {
        $orderIds = [0];
        $data = [];
        foreach($orders as $item) {
            $orderIds[] = $item['id'];
            $save = [
                'settlement_id' => $this->settlement_id,
                'order_id' => $item['id'],
                'platform_amount' => 0, //平台从服务商接受的钱
                'server_amount' => 0, //服务商从平台获取的钱
                'agent_amount' => 0, //代理商从平台结算的钱
                'salesman_amount' => 0, //业务员和代理商结算的
            ];
            if($item['payment_method'] == 1) {
                $save['server_amount'] = $this->amountCalculation($item['amount'], $item['server_rate']);
            } else {
                $save['platform_amount'] = $this->amountCalculation($item['amount'], (100 - $item['server_rate']));
            }
            $save['agent_amount'] = $this->amountCalculation($item['amount'], $item['agent_rate']);
            $save['salesman_amount'] = $this->amountCalculation($item['amount'], $item['rate']);
            $data[] = $save;
        }

        //保存记录
        $rs = $this->orderSettlement->insertBatch($data);
        if($rs === false) {
            return false;
        }
        //修改订单状态为已结算
        $rs = $this->orderModel->whereIn('id', $orderIds)->update(['is_settlement' => 1]);
        if($rs === false) {
            return false;
        }
        return true;
    }
    
    /**
     * 创建结算单
     * @return type
     */
    protected function createSettlement() {
        
        $data = [
            'name' => date('Y年m月结算清单', $this->preDayTime),
            'month' => date('Ym', $this->preDayTime),
        ];
        $settlement = $this->settlementModel->whereExtend(['month' => $data['month']])->select('id')->first();
        if($settlement) {
            $this->settlement_id = $settlement->id;
            return true;
        }
        $this->settlement_id = $this->settlementModel->saveItem($data);
        return $this->settlement_id !== false ? true : false;
    }
    
    protected function checkCurrentDayIsRun() {
        if(!Cache::has('settlement_day')) {
            return true;
        }
        $oldMonth = Cache::get('settlement_day');
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
        return Cache::forever('settlement_day', date('Ymd', $this->preDayTime));
    }
}
