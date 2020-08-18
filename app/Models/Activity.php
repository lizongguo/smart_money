<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends BaseModel
{
    protected $table = 'activity';
    protected $primaryKey = 'id';
    
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function shops() : BelongsTo
    {
        return $this->belongsTo(Shops::class, 'shop_id');
    }
    
    /**
     * 扩展列表查询
     * @param type $sh 查询条件
     * @param type $all 是否全部查询
     * @param type $limit 每页数
     * @param type $field 查询字段
     * @return type
     */
    public function getList($sh=[], $all = false, $limit = 20, $field = null)
    {
        $rs = parent::getList($sh, $all, $limit, $field);
        foreach($rs as &$item){
            $item->shops = $item->shops;
        }
        return $rs;
    }
    
    /**
     * @param $method
     */
    public function setCategoryIdsAttribute($method)
    {
        if (is_array($method)) {
            $this->attributes['category_ids'] = implode(',', $method);
        }elseif(is_string($method)) {
            $this->attributes['category_ids'] = $method;
        }
    }

    /**
     * @param $method
     *
     * @return array
     */
    public function getCategoryIdsAttribute($method)
    {
        if (is_string($method)) {
            return array_filter(explode(',', $method));
        }
        return $method;
    }
    
    /**
     * 获取店铺的优惠活动
     * @param type $shop_id
     */
    public function getActivityByShopId ($shop_id)
    {
        $now = date('Y-m-d');
        $activity = parent::getList([
            'shop_id' => $shop_id,
            'start_date' => ['conn' => '<=', 'value' => $now],
            'end_date' => ['conn' => '>=', 'value' => $now],
            ], 
            true,
            0,
            [
            'id', 'category_ids', 'activity_name', 'content', 'type', 'full_amount', 'minus_amount', 'discount'
        ]);
        foreach ($activity as &$item) {
            $item->category_ids;
        }
        return $activity;
    }
    
}
