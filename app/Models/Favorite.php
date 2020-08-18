<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Favorite extends BaseModel
{
    protected $table = 'favorite';
    protected $primaryKey = 'id';
    protected $isDeleted = false;


    function getList($sh=[], $all = false, $limit = 20, $field = null) {
        $obj = $this->whereExtend($sh);
        $obj = $obj->join('jobs','favorite.job_id','jobs.job_id');

        if (isset($sh['order']) && $sh['order']) {
            $obj = $obj->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
        } else {
            $obj = $obj->orderBy($this->table . '.' . $this->primaryKey, 'desc');
        }


        if (!empty($field) && is_array($field)) {
            call_user_func_array(array($obj, 'select'), $field);
        }

        if (!$all) {
            $rs = $obj->paginate($limit);
        } else {
            $rs = $obj->get();
        }

        return $rs;
    }

}