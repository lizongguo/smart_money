<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DB;

class News extends BaseModel
{
    protected $table = 'news';
    protected $primaryKey = 'id';
    
    protected $fillable = ['title', 'category_id', 'keywords', 'description', 'thumb', 'content', 'recommend', 'hits', 'sort', 'state'];
    
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
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
            $rs->category;
        }
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
//        $field = array_merge($field, ['id', 'name', 'phone', 'email', 'role', 'created_at', 'remarks']);
        $rs = parent::getList($sh, $all, $limit, $field);
        foreach($rs as &$item){
            $item->category = $item->category;
            $item->url = route('web.news.detail', ['id' => $item->id]);
        }
        return $rs;
    }
    
}
