<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderGoods extends BaseModel
{
    protected $table = 'order_goods';
    protected $primaryKey = 'id';
    protected $isDeleted = true;
    
    /**
     * 通过order ids 获取商品list
     * @param type $order_ids
     * @return type
     */
    public function getOrderGoodsByOrderIds ($order_ids)
    {
        $obj = $this->select('order_goods.id', 'order_goods.order_id', 'order_goods.goods_id', 'order_goods.shop_id', 'order_goods.goods_name', 
            'order_goods.img', 'order_goods.goods_specs_id', 'order_goods.sell_price', 'order_goods.spec_str', 
            'order_goods.goods_num', 'order_goods.return_num', 'order_goods.buy_times', 'order_goods.state', 
            'goods_category.id as category_id', 'goods_category.name as category_name');
        if (is_array($order_ids)) {
           $obj->whereIn('order_id', $order_ids);
        } else if (is_numeric($order_ids)){
            $obj->where('order_id', $order_ids);
        }
        $obj->leftJoin('goods', 'goods.id', '=', 'order_goods.goods_id');
        $obj->leftJoin('goods_category', 'goods_category.id', '=', 'goods.category_id');
        $obj->orderBy('order_goods.id', 'asc');
        $result = $obj->get();
        $data = [];
        foreach($result as $item) {
            $item->img = asset($item->img);
            $data[$item->order_id][] = $item;
        }
        return $data;
    }
    
    public function getOrderTotalAmountAndGoodsNum($order_id)
    {
        $obj = $this->select(DB::raw('sum((goods_num-return_num) * sell_price) as amount'), 
            DB::raw('sum(goods_num - return_num) as goods_num'))
            ->where('order_id', $order_id)
            ->where('deleted', 0)
            ->first();
        
        return $obj;
    }
}
