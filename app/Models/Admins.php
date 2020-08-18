<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\Traits\BaseModelTrait;
use App\Http\Controllers\Traits\HasPermissions;


class Admins extends Authenticatable
{
    use HasPermissions;
    use BaseModelTrait {
        BaseModelTrait::getList as getParentList;
        BaseModelTrait::getOne as getParentOne;
        BaseModelTrait::saveItem as saveParentItem;
    }
    
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    protected $fillable = ['name', 'password', 'email', 'avatar', 'phone', 'role', 'remarks', 'access_token', 'remember_token'];
    
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function roles() : BelongsTo
    {
        return $this->belongsTo(Roles::class, 'role');
    }
    
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function permissions() : belongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'admin_permissions', 'admin_id', 'permission_id');
//        return $this->hasMany(AdminPermissions::class, 'admin_id', 'id');
    }
    
    /**
     * 对已有的数据扩展
     * @param type $id
     * @return type
     */
    public function getOne($id)
    {
        $rs = $this->getParentOne($id);
        if (!!$rs) {
            $rs->roles = $rs->roles;
            $rs->permissions = $rs->permissions->pluck('id')->toArray();
        }
//        dd($rs);
        return $rs;
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
        $field = array_merge($field, ['id', 'name', 'phone', 'email', 'role', 'created_at', 'remarks']);
        $rs = $this->getParentList($sh, $all, $limit, $field);
        foreach($rs as &$item){
            $item->roles = $item->roles;
        }
        return $rs;
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
            $id = $this->saveParentItem($data);
            $permissions = preg_split("/,/", $data['permissions'], -1, 1);
            $oldIds = \DB::table('admin_permissions')
                ->where('admin_id', $id)
                ->pluck('permission_id')->toArray();
            if(count($oldIds) < 1) {
                $oldIds = [];
            }
            //判断老数据中已经删除的数据
            $reducesIds = array_diff($oldIds, $permissions);
            if(count($reducesIds) > 0) {
                \DB::table('admin_permissions')
                    ->where('admin_id', $id)
                    ->whereIn('permission_id', $reducesIds)
                    ->delete();
            }
            
            //添加新关系数据
            $incsreaseIds = array_diff($permissions, $oldIds);
            if(count($incsreaseIds) > 0) {
                $data = [];
                $now = date('Y-m-d H:i:s');
                foreach($incsreaseIds as $permission_id) {
                    $data[] = [
                        'admin_id' => $id,
                        'permission_id' => $permission_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                \DB::table('admin_permissions')->insert($data);
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
     * 忘记密码|找回密码
     * @param type $phone
     * @param type $password
     * @param type $code
     * @return boolean
     */
    public function forget ($phone, $password, $code) {
        $user = $this->where('phone', $phone)
            ->where('deleted', 0)
            ->where('state', 1)
            ->whereIn('role', ['4', '6'])
            ->first();
        //验证用户是否存在， 验证码是否有效
        if(!$user || !$this->checkCode($phone, $code, 'forgetpwd')) {
            return false;
        }
        
        //修改密码
        $rs = $this->where('id', $user->id)->update(['password' => md5($password)]);
        if($rs === false) {
            return false;
        }
        
        //删除redis中短信验证码。
        $redisKey = config("rediskeys.verification_forgetpwd_hash");
        Redis::hdel($redisKey, $phone);
        
        return true;
    }
    
    /**
     * 通过自动登录remember token 查询管理用户。
     * @param type $remember_token
     * @return type
     */
    public function getAdminByRememberToken($remember_token)
    {
        return $this->where('remember_token', $remember_token)->first();
    }
}
