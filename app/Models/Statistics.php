<?php

namespace App\Models;

use DB;

class Statistics extends BaseModel
{
    protected $table = 'order_statistics';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    /**
     * 获取店铺指定月份的销售数据
     * @param type $shop_id
     * @param type $month  201812
     * @return type
     */
    public function getListByMonth($shop_id, $month)
    {
        $data = $this->where('month', $month)
            ->where('shop_id', $shop_id)
            ->orderby('day', 'asc')
            ->get()
            ->toArray();
        return $data;
    }
    
}
