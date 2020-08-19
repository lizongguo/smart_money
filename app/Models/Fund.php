<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Fund extends BaseModel
{
    protected $table = 'fund';
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


    public function saveFound($data)
    {
        \DB::statement('truncate table found');
        \DB::beginTransaction();
        try {
            foreach ($data as $k => $v) {
                if($k==0) {
                    continue;
                }
                $insert = [
                    'found_no' => $v[0],
                    'current_name' => (string)$v[1],
                    'ever_name' => (string)$v[2],
                    'current_gp' => (string)$v[3]
                ];
                \DB::table('found')->insert($insert);
            }
            \DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
    }

    public function getFoundById($id)
    {
        $found = $this->select('*')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();
        if (!$found) {
            return false;
        }
        return $found;
    }
}
