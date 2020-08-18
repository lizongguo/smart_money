<?php

namespace App\Models;

use App\Http\Controllers\Traits\ModelTree;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsCategory extends BaseModel
{
    use ModelTree;
    
    protected $table = 'goods_category';
    protected $primaryKey = 'id';
    
    protected $fillable = ['name', 'img','parent_id', 'sort', 'state'];
    
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
//    public function shops() : BelongsTo
//    {
//        return $this->belongsTo(Shops::class, 'shop_id');
//    }
    
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
//        foreach($rs as &$item){
//            $item->shops = $item->shops;
//        }
        return $rs;
    }
}
