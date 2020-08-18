<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shops extends BaseModel
{
    protected $table = 'shops';
    protected $primaryKey = 'id';
    
    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function saveShop($data) {
        
        \DB::beginTransaction();
        try {
            $id = $this->saveItem($data);
            
            if (!isset($data['id']) || empty($data['id'])) {
                //添加默认设置
                $settingModel = new Setting();
                $settingModel->setShopDefaultOption($id);
            }
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return $id;
    }
    
    public function deletedItem($ids){
        if (is_array($ids)) {
            $values = ['conn' => 'in', 'value' => $ids];
        }else if (!empty($ids)){
            $values = $ids;
        }else {
            return false;
        }
        $item = [$this->getPrimaryKey() => $values];
        
        if(isset($this->isDeleted) && $this->isDeleted == true) {
            $rs = $this->whereExtend($item)->update(['deleted' => 1]);
        } else {
            $rs = $this->whereExtend($item)->delete();
        }
        //删除店铺设定
        $settingModel = new Setting();
        $settingModel->whereExtend(['shop_id' => $values])->where('shop_id', '>', '0')->delete();
        
        return $rs;
    }
    
}
