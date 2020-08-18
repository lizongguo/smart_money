<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DB;
class GoodsSpecs extends BaseModel
{
    
    protected $table = 'goods_specs';
    protected $primaryKey = 'id';
    
    
    /**
     * Log belongs to goods.
     *
     * @return BelongsTo
     */
    public function goods() : BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }
    
    public function saveGoodsSpecs($goods, $products) {
        $add = [];
        $this->where('goods_id', $goods['id'])->update(['deleted' => 1]);
        $date = date('Y-m-d H:i:s');
        foreach ($products as $product) {
            if ($product['id'] && $item = $this->where('id', $product['id'])->first()) {
                $item->goods_id = $goods['id'];
                $item->spec_str = $product['spec_str'];
                $item->sell_price = $product['sell_price'];
                $item->deleted = 0;
                $rs = $item->save();
                if ($rs === false) {
                    return false;
                }
            }else {
                $add[] = [
                    'goods_id' => $goods['id'],
                    'spec_str' => $product['spec_str'],
                    'sell_price' => $product['sell_price'],
                    'created_at' => $date,
                    'updated_at' => $date,
                    'deleted' => 0,
                ];
            }
        }
        
        if (count($add)) {
            if ($this->insertBatch($add) === false) {
                return false;
            }
        }
        return true;
    }
    
    
    /**
     * 查询产品 通过goods ids
     * @param type $goods_ids
     * @return type
     */
    public function getSpecsByGoodsIds ($goods_ids)
    {
        if(count($goods_ids) < 1) {
            $goods_ids = [0];
        }
        $data = $this->select('id', 'spec_str', 'sell_price', 'goods_id')
            ->whereIn('goods_id', $goods_ids)
            ->where('deleted', 0)
            ->get();
        $map = [];
        foreach($data as $prodects) {
            $map[$prodects->goods_id][] = $prodects;
        }
        return $map;
    }
    
    /**
     * 通过产品id 获取 产品情报
     * @param type $ids
     * @return type
     */
    public function getSpecsByIds ($ids) {
        $data = $this->select('goods_specs.id', 'goods_id', 'spec_str', 'goods_specs.sell_price', 'goods_name', 'goods.img')
            ->join('goods', 'goods.id', '=', 'goods_id')
            ->whereIn('goods_specs.id', $ids)
            ->where('goods_specs.deleted', 0)
            ->where('is_shelves', 1)
            ->get();
        $result = [];
        foreach ($data as $item) {
            $result[$item->id] = $item;
        }
        return $result;
    }
}
