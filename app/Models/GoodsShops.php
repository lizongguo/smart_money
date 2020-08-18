<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsShops extends BaseModel
{
    
    protected $table = 'goods_shops';
    protected $primaryKey = 'id';
    protected $isDeleted = 0;
    public $timestamps = false;


    /**
     * Log belongs to goods.
     *
     * @return BelongsTo
     */
    public function goods() : BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }
    
    /**
     * Log belongs to goods.
     *
     * @return BelongsTo
     */
    public function shops() : BelongsTo
    {
        return $this->belongsTo(Shops::class, 'shop_id');
    }
    
}
