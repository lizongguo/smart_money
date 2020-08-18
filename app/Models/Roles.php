<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Roles extends BaseModel
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    /**
     * @var array
     */
    protected $fillable = ['name', 'slug'];
    
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function menus() : BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id');
    }
    
    /**
     * Check user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can($permission_id) : bool
    {
        return $this->permissions()->where('id', $permission_id)->exists();
    }

    /**
     * Check user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot($permission_id) : bool
    {
        return !$this->can($permission_id);
    }
    
    /**
     * 数据保存 过滤掉不存在的字段
     * @param type $data
     * @return boolean
     */
    public function saveItem($data) {
        \DB::beginTransaction();
        try {
            $id = parent::saveItem($data);
            
            $permissions = preg_split("/,/", $data['permissions'], -1, 1);
            $oldIds = \DB::table('role_permissions')
                ->where('role_id', $id)
                ->pluck('permission_id')->toArray();
            if(count($oldIds) < 1) {
                $oldIds = [];
            }
            //判断老数据中已经删除的数据
            $reducesIds = array_diff($oldIds, $permissions);
            if(count($reducesIds) > 0) {
                \DB::table('role_permissions')
                    ->where('role_id', $id)
                    ->whereIn('permission_id', $reducesIds)
                    ->delete();
            }
            
            //添加新关系数据
            $incsreaseIds = array_diff($permissions, $oldIds);
            if(count($incsreaseIds) > 0) {
                $data = [];
                foreach($incsreaseIds as $permission_id) {
                    $data[] = [
                        'role_id' => $id,
                        'permission_id' => $permission_id,
                    ];
                }
                \DB::table('role_permissions')->insert($data);
            }
            
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return $id;
    }
    
    
    
}
