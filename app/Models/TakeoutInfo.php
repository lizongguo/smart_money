<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TakeoutInfo extends BaseModel
{
    protected $table = 'takeout_info';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    /**
     * @var $stateArr 
     */
    protected $stateArr = [
        '0' => ['name' => '待接单', 'info' => '订单已成功付款:{payment_amount}元，等待商家接单。'],
        '1' => ['name' => '已接单', 'info' => '订单已被商家接单，等待分配骑手。'],
        '2' => ['name' => '已分配', 'info' => '订单已分配给配送员【{express_name}】，配送员配送中。'],
        '3' => ['name' => '已取餐', 'info' => '订单已取餐，订单正在配送。'],
//        '4' => ['name' => '正在返回', 'info' => '司机已接车，正在返回中。'],
//        '5' => ['name' => '已接车', 'info' => '商家【{service}】，已成功接车，准备进行评估。'],
//        '6' => ['name' => '已评估', 'info' => '商家【{service}】，已出评估结果，价格：{cost}。'],
//        '7' => ['name' => '处理中', 'info' => '评估结果已确认,正在处理中。'],
//        '8' => ['name' => '待交车', 'info' => '商家【{service}】，处理完毕，等待交车。'],
//        '9' => ['name' => '送车中', 'info' => '送车进行中。'],
        
        '96' => ['name' => '已拒绝', 'info' => '订单被商家拒绝，等待商家退款。'],
        '97' => ['name' => '已退款', 'info' => '订单已成功过退款。'],
        '98' => ['name' => '已取消', 'info' => '订单已被取消。'],
        '99' => ['name' => '已完成', 'info' => '订单配送已完成。'],
    ];
    
    public function getRecordInfo($state = 1, $params = [])
    {
        $info = $this->stateArr[$state]['info'];
        $strtr = [];
        foreach ($params as $key => $val) {
            $strtr['{' . $key . '}'] = $val;
        }
        return preg_replace("#\{\[^\}]*}#", '', strtr($info, $strtr));
    }
    
    /**
     * 
     * @param type $state
     * @param type $user
     * @param type $order_id
     * @param type $params
     * $params = [
        'express_name' =>  '配送员名称',
        'memo' => "已经接车",
        'payment_amount' => '0', //订单金额
    ],
     * @return type
     */
    public function addRecord ($state, $operator, $order_id, $params = null)
    {
        $data = [
            'message' => $this->getRecordInfo($state, $params),
            'takeout_id' => 0,
            'order_id' => $order_id,
            'state' => $state,
            'operator' => $operator,
            'options' => json_encode($params)
        ];
        $id = $this->saveItem($data);
        
        return $id;
    }
    
}
