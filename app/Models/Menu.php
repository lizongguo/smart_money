<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Controllers\Traits\ModelTree;
use DB;

class Menu extends BaseModel
{
    use ModelTree;
    
    protected $table = 'menu';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri', 'permission'];
    
    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function roles() : belongsToMany
    {
        return $this->belongsToMany(Roles::class, 'role_menu', 'menu_id', 'role_id');
//        return $this->hasMany(RoleMenu::class, 'menu_id', 'id');
    }
    
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsTo
    {
        return $this->belongsTo(Permissions::class, 'permission_id', 'id');
    }
    
    /**
     * 对已有的数据扩展
     * @param type $id
     * @return type
     */
    public function getOne($id)
    {
        $rs = parent::getOne($id);
        if (!!$rs) {
            $rs->roles = $rs->roles->pluck('id')->toArray();
//            $rs->permissions = $rs->permissions;
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
            if (empty($data[$this->primaryKey])) {
                $data['order'] = 999;
            }
            $id = parent::saveItem($data);
            $permissions = preg_split("/,/", $data['roles'], -1, 1);
            $oldIds = \DB::table('role_menu')
                ->where('menu_id', $id)
                ->pluck('role_id')->toArray();
            if(count($oldIds) < 1) {
                $oldIds = [];
            }
            //判断老数据中已经删除的数据
            $reducesIds = array_diff($oldIds, $permissions);
            if(count($reducesIds) > 0) {
                \DB::table('role_menu')
                    ->where('menu_id', $id)
                    ->whereIn('role_id', $reducesIds)
                    ->delete();
            }
            
            //添加新关系数据
            $incsreaseIds = array_diff($permissions, $oldIds);
            if(count($incsreaseIds) > 0) {
                $data = [];
                $now = date('Y-m-d H:i:s');
                foreach($incsreaseIds as $permission_id) {
                    $data[] = [
                        'menu_id' => $id,
                        'role_id' => $permission_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                \DB::table('role_menu')->insert($data);
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
