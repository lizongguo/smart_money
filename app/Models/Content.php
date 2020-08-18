<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Content extends BaseModel
{
    protected $table = 'content';
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

    public function saveData($data) {
        if ($data['id']) {
            $id = $data['id'];
            $update = [
                'title' => $data['title'],
                'type' => $data['type'],
                'content' => $data['content'],
            ];
            return $this->where('id', $id)->update($update);
        } else {
            $insert = [
                'title' => $data['title'],
                'type' => $data['type'],
                'content' => $data['content'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->insert($insert);
        }
    }

    public function getContentList($type, $sh)
    {

        $obj = $this->select('*')
            ->where('type', $type)
            ->where('deleted', 0);
        if ($sh['title']) {
            $obj->where('title', 'like', '%'.$sh['title'].'%');
        }
        return $obj->get()->toArray();
    }
}
