<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DB;

class OrderTakeout extends BaseModel
{
    protected $table = 'order_takeout';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function order() : belongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
    
    /**
     * 通过order ids 获取商品list
     * @param type $order_ids
     * @return type
     */
    public function getOrderTakeoutByOrderIds ($order_ids)
    {
        $result = $this->select('id', 'order_id', 'takeout_type', 'take_cate', 'waiter_id', 'accept_name',
            'gender', 'phone', 'delivery_time', 'delivery_address', 'long', 'lat', 
            'express_code', 'express_name', 'express_phone', 'takeout_state', 'takeout_amount', 'tableware_amount')
            ->whereIn('order_id', $order_ids)
            ->get();
        $data = [];
        foreach($result as $item) {
            $data[$item->order_id] = $item;
        }
        return $data;
    }
    
    /**
     * @param type $order_id
     * @return type
     */
    public function getTakeoutByOrderId($order_id)
    {
        $result = $this->where('order_id', (int) $order_id)->first();
        return $result;
    }
}
