<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Http\Controllers\Traits\BaseModelTrait;

class Job extends BaseModel
{
    use BaseModelTrait {
        BaseModelTrait::saveItem as saveParentItem;
    }

    protected $table = 'jobs';
    protected $primaryKey = 'job_id';
    protected $isDeleted = true;


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }


    function getList($sh=[], $all = false, $limit = 20, $field = null, $data=[], $once = false, $time = false) {
        $obj = $this;
        if (isset($data['word']) && $data['word']) {
            $word = $data['word'];
            if ($once) {
                $obj = $obj->where(
                    function ($query) use ($word) {
                        foreach ($word as $v) {
                            $query->orWhere("skill_experience", 'like', "%{$v}%");
                            $query->orWhere("jp_detail", 'like', "%{$v}%");
                            $query->orWhere("welfare", 'like', "%{$v}%");
                            $query->orWhere("interview_process", 'like', "%{$v}%");
                            $query->orWhere("working_time_all", 'like', "%{$v}%");
                            $query->orWhere("yearly_income_memo", 'like', "%{$v}%");
                            $query->orWhere("en_level_other", 'like', "%{$v}%");
                            $query->orWhere("age_other", 'like', "%{$v}%");
                            $query->orWhere("working_place_other", 'like', "%{$v}%");
                            $query->orWhere("working_time_holiday", 'like', "%{$v}%");
                            $query->orWhere("others", 'like', "%{$v}%");
                        }
                    }
                );
            } else {
                $objOr = $this->select(['*'])->where(
                    function ($query) use ($word) {
                        foreach ($word as $v) {
                            $query->orWhere("position", 'like', "%{$v}%");
                        }
                    }
                );

                //DB::raw('2 AS sort_sort')
                $wordJp = $data['word_jp'];
                $objAnd = $this->select(['*'])->where(
                    function ($query) use ($word, $wordJp) {
                        foreach ($word as $v) {
                            if (!in_array($v, $wordJp)) {
                                $query->where("position", 'like', "%{$v}%");
                            }
                        }
                        $query->where(function ($query1) use ($wordJp) {
                            foreach ($wordJp as $v) {
                                $query1->orWhere("position", 'like', "%{$v}%");
                            }
                        });
                    }
                );

                $newModel = $objAnd->union($objOr);

                $newModelSql = $newModel->toSql();

                $obj = $obj->from(DB::raw("($newModelSql) as jobs"))->setBindings( $objAnd->getBindings());
            }
        }

        $obj = $this->whereExtend($sh, $obj);

        if (!$field) {
            $field = [DB::raw('DISTINCT job_id'), "account_code", "job_code", "position"];
        }

        if (isset($data['wage_type']) && $data['wage_type']) {
            $keyFrom = 'from';
            $keyTo = 'to';
            if ($data['wage_type'] == 1 && $data[$keyFrom] && $data[$keyTo]) {
                $yearly_from = $data[$keyFrom];
                $yearly_to = $data[$keyTo];

                $obj = $obj->where(
                    function ($query) use ($yearly_from, $yearly_to) {
                        $query->whereBetween("yearly_income_low", [$yearly_from, $yearly_to]);
                        $query->orWhereBetween("yearly_income_up", [$yearly_from, $yearly_to]);
                    }
                );
            }
            if ($data['wage_type'] == 2 && $data[$keyFrom] && $data[$keyTo]) {
                $yearly_from = $data[$keyFrom];
                $yearly_to = $data[$keyTo];
                $obj = $obj->where(
                    function ($query) use ($yearly_from, $yearly_to) {
                        $query->whereBetween("monthly_income_low", [$yearly_from, $yearly_to]);
                        $query->orWhereBetween("monthly_income_up", [$yearly_from, $yearly_to]);
                    }
                );
            }
            if ($data['wage_type'] == 3 && $data[$keyFrom] && $data[$keyTo]) {
                $yearly_from = $data[$keyFrom];
                $yearly_to = $data[$keyTo];
                $obj = $obj->where(
                    function ($query) use ($yearly_from, $yearly_to) {
                        $query->whereBetween("hourly_from", [$yearly_from, $yearly_to]);
                        $query->orWhereBetween("hourly_to", [$yearly_from, $yearly_to]);
                    }
                );
            }
        }
        if (!(isset($data['word']) && $data['word'])) {
            if (isset($sh['order']) && $sh['order']) {
                //应募管理按新增数排序
                if ($sh['order']['field'] == 'record_count_new') {
                    $obj = $obj->orderBy($this->table . '.' . 'record_count_new', 'desc');
                    $obj = $obj->orderBy($this->table . '.' . $this->primaryKey, 'desc');
                    $obj = $obj->where('record_count', '>', 0);
                } elseif ($sh['order']['field'] == 'updated_at') {
                    $obj = $obj->orderBy($this->table . '.' . 'updated_at', 'desc');
                    $obj = $obj->orderBy($this->table . '.' . $this->primaryKey, 'desc');
                } else {
                    $obj = $obj->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
                }
            } else {
                $obj = $obj->orderBy($this->table . '.' . $this->primaryKey, 'desc');
            }
        } else {
            //$obj = $obj->orderBy($this->table . '.' . $this->primaryKey, 'desc');
        }

        if (!empty($field) && is_array($field)) {
            call_user_func_array(array($obj, 'select'), $field);
        }
        //print_r($obj->toSql());exit;
        if ($time) {
            $date =  date("Y-m-d");
            $obj = $obj->where(
                function ($query) use ($date) {
                    $query->where(function ($query1) use ($date) {
                        $query1->where("job_period_start", "<=", $date);
                        $query1->where("job_period_end", ">=", $date);
                        $query1->where("job_period_type", "=", 1);
                    });
                    $query->orWhere("job_period_type", "=", 2);
                }
            );
        }

        if (!$all) {
            $rs = $obj->paginate($limit);
        } else {
            $rs = $obj->get();
        }

        return $rs;
    }



    public function saveItem($data) {
        $id = $this->saveParentItem($data);

        $update = [
            'job_id' => $id,
            'account_code' => $this->getNewCodeId('job', '', $id),
        ];

        $this->saveParentItem($update);

        return $id;
    }

    function detail($id = 0) {
        $obj = $this;
        $date =  date("Y-m-d");
        $obj = $obj->where(
            function ($query) use ($date) {
                $query->where(function ($query1) use ($date) {
                    $query1->where("job_period_start", "<=", $date);
                    $query1->where("job_period_end", ">=", $date);
                    $query1->where("job_period_type", "=", 1);
                });
                $query->orWhere("job_period_type", "=", 2);
            }
        );

        return $obj->where('job_id', $id)->where('deleted', 0)->first();
    }

}