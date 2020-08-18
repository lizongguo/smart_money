<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends BaseModel
{
    protected $table = 'booking';
    protected $primaryKey = 'id';
    protected $isDeleted = false;


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
     * 获取用户预约数
     * @param type $user_id
     */
    public function getBookingNum($user_id)
    {
        $num = $this->where('user_id', $user_id)->count();
        return $num;
    }
    
    /**
     * 获取用户预约数
     * @param type $user_id
     */
    public function getBookingWaitNum($user_id)
    {
        $num = $this->where('user_id', $user_id)->where('state', '<', '2')->count();
        return $num;
    }
    
    /**
     * 预约就餐绑定桌号
     * @param type $id
     * @param type $desk_id
     */
    public function eat($id, $desk_id)
    {
        $save = [
            'id' => $id,
            'desk_ids' => $desk_id,
            'state' => 2
        ];
        \DB::beginTransaction();
        try {
            //保存数据
            $id = $this->saveItem($save);
            
            $deskModel = new Desks;
            $desk = $deskModel->where('id', $desk_id)->first();
            
            //修改预约点餐的订单座位号
            $orderModel = new Orders;
            $orderModel->where('booking_id', $id)->where('desk_id', '')->update([
                'desk_id' => $desk_id,
                'desk_alias' => $desk->alias,
            ]);
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            Log::error($ex);
            return false;
        }
        return $id;
    }
    
}
