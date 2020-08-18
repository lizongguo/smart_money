<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01 
 * Time: 15:01
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Resume\SavePdfRequest;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\Roles;
use Validator;

class ResumeController extends BaseController
{

    public function __construct(Resume $model) {
        parent::__construct();
        $this->model = $model;

        view()->share('jobInfo', config("code.job"));
        view()->share('resumeInfo', config("code.resume"));
        view()->share('pageTitle', $this->pageTitle);
    }

    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['name'])) {
            $sh['name'] = ['conn' => 'orlike', 'filed' => ["name"], 'value' => str_replace(['\\', '%', '_'], ['\\'.'\\', '\\'.'%', '\\'.'_'], $data['name']) ];
        }

        if (!empty($data['created_start']) || !empty($data['created_end'])) {
            $data['created_start'] = $data['created_start'] ? $data['created_start'] . ' 00:00:00' : "2000-01-01";
            $data['created_end'] = $data['created_end'] ? $data['created_end']. ' 23:59:59' : "2099-01-01";
            $sh['created_at'] = ['conn' => 'between', 'value' => [$data['created_start'], $data['created_end']]];
        }

        unset($sh['created_start'], $sh['created_end']);

        return $sh;
    }

    function items(Request $request) {
        $rs = parent::items($request, true);
        $resumeInfo = config("code.resume");

        foreach ($rs['data'] as &$v) {
            $v['sex'] = $resumeInfo['sex'][$v['sex']];
            if ($v['birthday'] && $v['birthday'] != "0000-00-00") {
                $age_str = floor((time() - strtotime($v['birthday'])) / (3600 * 24 * 365)) . "歳";
            } else {
                $age_str = "不明";
            }
            $v['age'] = $age_str;
            $v['nationality'] = $resumeInfo['nationality'][$v['nationality_id']] != "その他" ? $resumeInfo['nationality'][$v['nationality_id']] : $v['nationality'];
            $v['address'] = $resumeInfo['address'][$v['address']] . ($v['address'] == 1 && $v['address_id'] ? "[{$resumeInfo['country_city'][$v['address_id']]}]" : "");
            $v['employment_status_str'] = $resumeInfo['employment_status'][$v['employment_status']];
            $v['jp_level'] = $resumeInfo['jp_level_2'][$v['jp_level']];

            $v['visa_type'] = $resumeInfo['visa_type'][$v['visa_type']] == "その他" ? $v['visa_other'] : $resumeInfo['visa_type'][$v['visa_type']];

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
                $v['employment_status_extra'] = "{$v['employment_status_extra']}年";
            } else {
                if ($v['employment_status_extra'] == -1) {
                    $address_extra = "1年未満";
                } elseif ($v['employment_status_extra'] > 10) {
                    $address_extra = "10年以上";
                } else {
                    $address_extra = $v['employment_status_extra'] . "年";
                }
                $v['employment_status_extra'] = $address_extra;
            }

            $v['final_education'] = $resumeInfo['final_education'][$v['final_education']];
            $v['science_arts'] = $resumeInfo['science_arts'][$v['science_arts']];
            $v['interview'] = $resumeInfo['interview'][$v['interview']];
            $v['en_level'] = $resumeInfo['en_level'][$v['en_level']];

            $know_way_arr = $resumeInfo['know_way'];
            if ($v['know_way'] == 7 && $v['know_way_other']) {
                $know_way = $v['know_way_other'];
            } else {
                $know_way = $know_way_arr[$v['know_way']];
            }

            $v['know_way'] = $know_way;

            $it_skill_arr = $resumeInfo['it_skill'];
            $it_skillStr = "";
            if ($v['it_skill']) {
                foreach (explode(',', $v['it_skill']) as $val) {
                    if ($it_skill_arr[$val]) {
                        $it_skillStr .= $it_skill_arr[$val] . " ";
                    }
                }
                if ($v['it_skill_other']) {
                    $it_skillStr .= $v['it_skill_other'];
                }
            }

            $v['it_skill'] = $it_skillStr;

            $desired_fileds_arr = $resumeInfo['desired_fileds'];

            $desired_filedsStr = "";
            if ($v['desired_fileds']) {
                foreach (explode(',', $v['desired_fileds']) as $val) {
                    if ($desired_fileds_arr[$val]) {
                        $desired_filedsStr .= $desired_fileds_arr[$val] . " ";
                    }
                }
                if ($v['desired_fileds_other']) {
                    $desired_filedsStr .= $v['desired_fileds_other'];
                }
            }

            $v['desired_fileds'] = $desired_filedsStr;

            $desired_job_type_arr = $resumeInfo['desired_job_type'];

            $desired_job_typeStr = "";
            if ($v['desired_job_type']) {
                foreach (explode(',', $v['desired_job_type']) as $val) {
                    if ($desired_job_type_arr[$val]) {
                        $desired_job_typeStr .= $desired_job_type_arr[$val] . " ";
                    }
                }
                if ($v['desired_job_type_other']) {
                    $desired_job_typeStr .= $v['desired_job_type_other'];
                }
            }

            $v['desired_job_type'] = $desired_job_typeStr;


            $desired_place_arr = $resumeInfo['country_city'];
            $desired_placeStr = "";
            if ($v['desired_place_ids']) {
                $desired_place_ids_arr =  explode(',', $v['desired_place_ids']);
                if (in_array("9999", $desired_place_ids_arr)) {
                    $desired_placeStr = "日本全国";
                } else {
                    foreach ($desired_place_ids_arr as $val) {
                        $desired_placeStr .= $desired_place_arr[$val] . " ";
                    }
                }
            }
            $v['desired_place_ids'] = $desired_placeStr;

            $v['pr_other'] = preg_replace("/[\r\n|\n]/iu", " ", $v['pr_other']);
            $v['pr_other'] = preg_replace("/,/iu", "，", $v['pr_other']);
            $v['account_code'] = $v['account_code'] ? $v['account_code'] : '';
        }

        return response()->json($rs);
    }

    public function input(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->getList(['resume_id' => $id])) {
            $data = $item[0];
        }
        $isEdit = $request->input('edit', 0);
        return view('admin.' . $this->viewName . '.input', ['data' => $data, 'is_edit' => $isEdit]);
    }

    public function savePdf(SavePdfRequest $request, Resume $resume)
    {
        $data = $request->validated();
        $resume->pdf_url = $data['pdf_url'];
        $resume->save();
        return response()->json([
            'status' => 200,
            'msg' => '保存に成功しました。'
        ]);
    }
    
}
