<?php

namespace App\Models;


class Address extends BaseModel
{
    protected $table = 'address';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    /**
     * 获取用户收货地址数
     * @param type $user_id
     */
    public function getAddressNum($user_id)
    {
        $num = $this->where('user_id', $user_id)->count();
        return $num;
    }
    
    /**
     * 地址添加 编辑功能
     * @param type $data
     */
    public function saveAddress($data)
    {
        \DB::beginTransaction();
        try {
            //默认地址
            if ($data['is_default'] == 1) {
                //修改现有默认地址为非默认
                $this->where('user_id', $data['user_id'])->where('is_default', 1)->update(['is_default' => 0]);
            }
            //保存现有数据
            $id = $this->saveItem($data);
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            Log::error($ex);
            return false;
        }
        return $id;
    }
}
