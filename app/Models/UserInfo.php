<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserInfo extends BaseModel
{
    protected $table = 'user_info';
    protected $primaryKey = 'id';
    
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
            $item->shops = $item->shops()->pluck('shop_name');
            $item->category = $item->category;
        }
        return $rs;
    }
    
    /**
     * 保存goods
     * @param type $data
     * @return boolean
     */
    public function saveGoods($data) {
        if (!isset($data['is_multiple_spec']) || $data['is_multiple_spec'] < 1){
            $data['products'][0]['sell_price'] = $data['sell_price'];
            $data['is_multiple_spec'] = 0;
        } else {
            $min = 0;
            foreach ($data['products'] as $product) {
                if ($min == 0 || $min > $product['sell_price']) {
                    $min = $product['sell_price'];
                }
            }
            $data['sell_price'] = $min;
        }
        $data['specs'] = json_encode($data['products']);
        
        if (!isset($data['state'])) {
            $data['state'] = 0;
        }
        if (!isset($data['recommend'])) {
            $data['recommend'] = 0;
        }
        if (!isset($data['is_shelves'])) {
            $data['is_shelves'] = 0;
        }
        
        $shops = explode(',', $data['shop_ids']);
        
        \DB::beginTransaction();
        try {
            $id = $this->saveItem($data);
            $specsModel = new GoodsSpecs();
            $goods = [
                'id' => $id,
            ];
            $rs = $specsModel->saveGoodsSpecs($goods, $data['products']);
            
            //保存店铺关联数据
            $goodsShopsModel = new GoodsShops();
            if (!empty($data['id'])) {
                //删除关联店铺的数据
                $goodsShopsModel->where('goods_id', $id)->delete();
            }
            $insert = [];
            if (count($shops) > 0) {
                foreach($shops as $shop_id) {
                    $insert[] = [
                        'goods_id' => $id,
                        'shop_id' => $shop_id
                    ];
                }
                $goodsShopsModel->insertBatch($insert);
            }
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return $id;
    }
    
    
    /**
     * 查询店铺所有商品
     * @param type $shop_sn
     * @return type
     */
    public function getGoodsByShopId ($shop_id, $is_shelves = 1)
    {
        $obj = $this->select('goods.id', 'category_id', 'img', 'goods_name', 'sell_price', 'is_multiple_spec', 'sale', 'recommend')
            ->where('shop_id', $shop_id)
            ->where('deleted', 0)
            ->join('goods_shops', 'goods_id', '=', 'goods.id');
        
        if($is_shelves > 0) {
            $obj->where('is_shelves', 1);
        }
        
        $data = $obj->orderBy('sale', 'DESC')->orderBy('recommend', 'DESC')->get();
        
        return $data;
    }
    
}
