<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Http\Controllers\Traits\BaseModelTrait;

class BaseModel extends Model {
    protected $isDeleted = true;
    
    use BaseModelTrait;

    function getList($sh=[], $all = false, $limit = 20, $field = null) {
        $obj = $this->whereExtend($sh);
        if (isset($sh['order']) && $sh['order']) {
            $obj = $obj->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
        } else {
            $obj = $obj->orderBy($this->table . '.' .$this->primaryKey, 'desc');
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

    function getNewCodeId($type = "company", $code="", $id = 0) {
        $new = "";
        $preg = "/(.*)-([\d]*)/u";
        switch ($type) {
            case "company_job" :
                preg_match($preg, $code, $arr);
                if ($code && $arr[1] && $arr[2]) {
                    $no = (int)$arr[2] + 1;
                    $new = $arr[1] . '-' . str_pad($no, 3, '0', STR_PAD_LEFT);
                } else {
                    $new = 'C' . str_pad($id, 4, '0', STR_PAD_LEFT) . '-001';
                }

                break;
            case "agent_job" :
                preg_match($preg, $code, $arr);
                if ($code && $arr[1] && $arr[2]) {
                    $no = (int)$arr[2] + 1;
                    $new = $arr[1] . '-' . str_pad($no, 3, '0', STR_PAD_LEFT);
                } else {
                    $new = 'A' . str_pad($id, 4, '0', STR_PAD_LEFT) . '-001';
                }

                break;
            case "company" :
                $new = 'C' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
            case "agent" :
                $new = 'A' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
            case "user" :
                $new = 'S' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
            case "job" :
                $new = 'J' . str_pad($id, 4, '0', STR_PAD_LEFT);

                break;
        }

        return $new;
    }
}