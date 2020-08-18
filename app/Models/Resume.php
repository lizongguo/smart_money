<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Resume extends BaseModel
{
    protected $table = 'resumes';
    protected $primaryKey = 'resume_id';
    protected $isDeleted = true;

    public function array_to_str($list) {
        $resumeInfo = config("code.resume");

        foreach ($list as &$v){
            $v['job_record_created_at'] = date("Y-m-d", strtotime($v['job_record_created_at']));
            $v['nationality_id'] = $resumeInfo['nationality'][$v['nationality_id']] != "その他" ? $resumeInfo['nationality'][$v['nationality_id']] : $v['nationality'];
            $v['sex'] = $resumeInfo['sex'][$v['sex']];
            $v['birthday'] = $v['birthday'] != '0000-00-00' ? floor((time() - strtotime($v['birthday'])) / (3600 * 24 * 365)) . "歳" : "不明";
            $v['address'] = $resumeInfo['address'][$v['address']];
            $v['jp_level'] = $resumeInfo['jp_level_2'][$v['jp_level']];
            $v['en_level'] = $resumeInfo['en_level'][$v['en_level']];

            if ($v['address_extra_2'] == -1) {
                $address_extra = "1年未満";
            } elseif ($v['address_extra_2'] == 99) {
                $address_extra = "10年以上";
            } elseif ($v['address_extra_2'] == 0) {
                $address_extra = "0年";
            } else {
                $address_extra = $v['address_extra_2'] . "年";
            }
            $v['address_extra_2'] = $address_extra;

            if ($v['employment_status'] == 1) {
                $address_extra = "卒業見込み：{$v['employment_status_extra']}年";
            } else {
                if ($v['employment_status_extra'] == -1) {
                    $address_extra = "1年未満";
                } elseif ($v['employment_status_extra'] > 10) {
                    $address_extra = "10年以上";
                } else {
                    $address_extra = $v['employment_status_extra'] . "年";
                }
                $address_extra = "仕事経験年数：{$address_extra}";
            }
            $v['employment_status'] = $resumeInfo['employment_status'][$v['employment_status']];
            $v['employment_status_extra'] = $address_extra;

            $v['science_arts'] = $resumeInfo['science_arts'][$v['science_arts']];
            $v['final_education'] = $resumeInfo['final_education'][$v['final_education']];
            $v['status_company_str'] = config("code.record.status_company")[$v['status_company']];

            $desired_place_ids = explode("," , $v['desired_place_ids']);
            $desired_place_ids_str = '';
            if (in_array('9999', $desired_place_ids)) {
                $desired_place_ids_str = "日本全国";
            } else {
                foreach($desired_place_ids as $val) {
                    $desired_place_ids_str .= ($resumeInfo['country_city'][$val] . " ");
                }
            }
            $v['desired_place_ids'] = $desired_place_ids_str;
        }

        return $list;
    }

    function getList($sh=[], $all = false, $limit = 20, $field = null) {
        $obj = $this->whereExtend($sh);
        $obj = $obj->join('users','users.id','resumes.user_id');

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
