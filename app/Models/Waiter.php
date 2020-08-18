<?php

namespace App\Models;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waiter extends BaseModel
{
    protected $table = 'user_waiter';
    protected $primaryKey = 'user_id';
    protected $isDeleted = false;
    
    public $timestamps = false;


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
    

    public function whereExtend($sh = [], $obj = null) {
        if (empty($obj)) {
            $obj = $this;
        }
        $obj = $obj->join('users', 'id', '=', 'user_id');
        //排除有些表没有deleted字段
        if (isset($this->isDeleted) && $this->isDeleted === true) {
            $obj = $obj->where($this->table .'.deleted', 0);
        }else {
            $obj = $obj->where(\DB::raw('1'), 1);
        }
        $this->parseSh($obj, $sh);
        return $obj;
    }
    
    /**
     * 通过id 获取指定数据
     * @param type $id
     * @return array $result
     */
    public function getOne($id) {
        $result = $this->whereExtend(['id' => $id])->first();
        return $result;
    }
    
    /**
     * @param type $phone
     * @param type $id
     * @return boolean
     */
    public function checkPhoneIsCookerUser($phone, $id = 0) {
        $num = $this->where('phone', $phone)->where('user_id', '!=', $id)->where('deleted', 0)->where('role', 1)->count();
        if ($num > 0) {
            //已注册
            return true;
        }else {
            //未注册厨师
            return false;
        }
    }
    
    /**
     * 数据保存 过滤掉不存在的字段
     * @param type $data
     * @return boolean
     */
    public function saveItem($data) {
        \DB::beginTransaction();
        try {
            if (!empty($data['password'])) {
                $data['password'] = \Hash::make($data['password']);
            }else{
                unset($data['password']);
            }
            $data['state'] = isset($data['state']) && $data['state'] > 0 ? 1 : 2;
            
            $userModel = new Users;
            $id = $userModel->saveItem($data);
            $data['user_id'] = $id;
            $data['permission'] = implode('', [ isset($data['cash']) ? 1 : 0 , isset($data['queue']) ? 1 : 0 ]);
            
            $user_id = $this->saveUnAutoItem($data);
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return $id;
    }
    
    /**
     * 查询指定角色、指定店铺的用户
     * @param type $shop_id
     * @param type $role
     * @return type
     */
    public function getUserByShopId ($shop_id)
    {
        $data = $this->select('id', 'push_token', 'username', 'phone', 'device_type', 'permission', 'role', 'wx_open_id',  'ali_open_id', 'shop_id')
            ->join('users', 'id', '=', 'user_id')
            ->where('shop_id', (int)$shop_id)
            ->where('state', 1)
            ->where('role', '1')
            ->get();
        foreach ($data as &$item) {
            $item->cash_permission = $item->permission{0};
            $item->queue_permission = $item->permission{1};
        }
        
        return $data;
    }
}
