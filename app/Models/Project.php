<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Project extends BaseModel
{
    protected $table = 'project';
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

    public function saveFoundData($data) {
        \DB::beginTransaction();
        try {
            $id = $data['id'];
            $update = [
                'company_name' => $data['company_name'],
                'company_name_eng' => $data['company_name_eng'],
                'address' => $data['address'],
                'website' => $data['website'],
                'state' => $data['state'],
                'state_val' => $data['state_val'],
                'listed_val' => $data['listed_val'],
                'content' => $data['content'],
                'shares_url' => $data['shares_url'],
                'file_path' => $data['file_path'],
            ];
            $this->where('id', $id)->update($update);
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return true;
    }


}
