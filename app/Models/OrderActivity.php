<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DB;

class OrderActivity extends BaseModel
{
    protected $table = 'order_activity';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function activity() : belongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
    
    /**
     * 通过order ids 获取商品list
     * @param type $order_ids
     * @return type
     */
    public function getOrderActivityByOrderIds ($order_ids)
    {
        $result = $this->select('id', 'order_id', 'activity_id', 'activity_type', 'full_amount', 'minus_amount',
            'discount', 'activity_amount', 'discount_amount', 'created_at')
            ->whereIn('order_id', $order_ids)
            ->get();
        $data = [];
        foreach($result as $item) {
            $data[$item->order_id] = $item;
        }
        return $data;
    }
    
    /**
     * 通过order id 获取优惠活动情报
     * @param type $order_id
     * @return type
     */
    public function getOrderActivityByOrderId ($order_id)
    {
        $result = $this->select('id', 'order_id', 'activity_id', 'activity_type', 'full_amount', 'minus_amount',
            'discount', 'activity_amount', 'discount_amount', 'created_at')
            ->where('order_id', $order_id)
            ->first();
        return $result;
    }
    
    /**
     * 创建订单优惠信息
     * @param type $order_id
     * @param type $activity_id
     * @return boolean|int
     */
    public function setOrderActivityByOrderIdAndActivityId($order_id, $activity_id)
    {
        //删除已有的优惠活动
        $this->where('order_id', $order_id)->delete();
        
        //计算优惠金额
        $activityModel = new Activity();
        $activity = $activityModel
            ->where('id', intval($activity_id))
            ->select('id', 'type', 'full_amount', 'minus_amount', 'discount', 'category_ids')
            ->first();
        if (!$activity) {
            return 0;
        }
        $cateIds = $activity->category_ids;
        if (count($cateIds) < 1) {
            return 0;
        }
        //获取满足优惠的商品id
        $goodsModel = new Goods();
        $goodsIds = $goodsModel->whereIn('category_id', $cateIds)->pluck('id')->toArray();
        
        $flag = false;
        if (count($goodsIds) > 0) {
            $orderGoodsModel = new OrderGoods();
            $activity_amount = $orderGoodsModel->select(\DB::raw('sum(sell_price * (goods_num - return_num)) as amount'))
                ->whereIn('goods_id', $goodsIds)
                ->where('order_id', $order_id)
                ->where('deleted', 0)
                ->first();
            $item = [
                'order_id' => $order_id,
                'activity_id' => $activity->id,
                'activity_type' => $activity->type,
                'full_amount' => $activity->full_amount,
                'minus_amount' => $activity->minus_amount,
                'discount' => $activity->discount,
                'activity_amount' => $activity_amount->amount,
                'discount_amount' => 0,
            ];
            
            if ($item['activity_type'] == 1 && $activity_amount->amount >= $activity->full_amount) { //满减
                $flag = true;
                $item['discount_amount'] = $activity->minus_amount;
            } else if ($item['activity_type'] == 2) { //折扣
                $flag = true;
                $item['discount_amount'] = round($activity_amount->amount * (1- ($activity->discount/10)), 2);
            }
            if($flag == true){
                //保存数据
                $rs = $this->saveItem($item);
                return $rs !== false ? $item['discount_amount'] : false;
            }
        }
        //不存在优惠券直接返回优惠金额为0
        return 0;
    }
}
