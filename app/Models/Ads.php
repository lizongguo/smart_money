<?php

namespace App\Models;

class Ads extends BaseModel
{
    protected $table = 'ads';
    protected $primaryKey = 'id';
    
    protected $fillable = ['id', 'name', 'thumb', 'type_id', 'url', 'content', 'order', 'state'];
    
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
        $field = array_merge($field, ['id', 'name', 'thumb', 'type_id', 'url', 'content', 'order', 'state', 'created_at']);
        $rs = parent::getList($sh, $all, $limit, $field);
        $ad_type = config('code.ad_type'); 
        foreach($rs as &$item){
            $item->type = $ad_type[$item->type_id] ?: '';
        }
        return $rs;
    }
}
