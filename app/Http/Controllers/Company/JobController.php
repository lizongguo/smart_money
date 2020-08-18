<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Company;

class JobController extends BaseController
{
    public function __construct(Request $request, Job $model) {
        parent::__construct();
        $this->model = $model;

        //获取全部的店铺
        view()->share('jobInfo', config("code.job"));
        view()->share('resumeInfo', config("code.resume"));
    }

    function index(Request $request) {
        $menu_active = "job";
        view()->share('menu_active', $menu_active);

        $data = $request->input("sh");
        $data['company_id'] = $this->user->id;
        $sh = $this->parseSearch($data);
        $sh['order'] = ['field' => 'jobs.updated_at', 'sort' => 'desc'];

        $list = $this->model->getList($sh, false, $this->pageCount, "*");

        return view('company.' . $this->viewName . '.index', ['list' => $list]);
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['position'])) {
            $sh['position'] = ['conn' => 'lk', 'value' => $data['position']];
        }
        return $sh;
    }
    
    
    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        return true;
        $valid = [
            'job_name' => 'required',
        ];
        
        $tips = [
            'job_name.required' => '',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }


    /**
     * 商品 创建 编辑
     * @param Request $request
     * @return type
     */
    public function input(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        $resumeInfo = config("code.resume");
        if($id > 0 && $item = $this->model->where('deleted', 0)->find($id)) {
            if ($item['company_id'] != $this->user->id) {
                return redirect('404');
            }
            $data = $item;
            $data->job_id = $id;

            $data->job_period_end = $data->job_period_end != "0000-01-01" ? $data->job_period_end : "";
            $data->job_period_start = $data->job_period_start != "0000-01-01" ? $data->job_period_start : "";
        }
        if ($request->isMethod('post')) {
            $data = $request->input('data');

            $job = $this->model->find($data['job_id']);
            if (!$job || $job['company_id'] != $this->user->id) {
                return response()->json([
                    'status' => 400,
                    'msg' => config('code.alert_msg.system.error'),
                ]);
            }

            $data['company_id'] = (int)$data['company_id'];
            $data['job_category'] = (int)$data['job_category'];
            $data['jp_level_2'] = (int)$data['jp_level_2'];
            $data['jp_level'] = (int)$data['jp_level'];
            $data['en_level'] = (int)$data['en_level'];
            $data['nationality'] = (int)$data['nationality'];
            $data['age_start'] = (int)$data['age_start'];
            $data['age_end'] = (int)$data['age_end'];
            $data['yearly_income_low'] = (int)$data['yearly_income_low'];
            $data['yearly_income_up'] = (int)$data['yearly_income_up'];
            $data['monthly_income_low'] = (int)$data['monthly_income_low'];
            $data['monthly_income_up'] = (int)$data['monthly_income_up'];
            $data['hourly_from'] = (int)$data['hourly_from'];
            $data['hourly_to'] = (int)$data['hourly_to'];
            $data['job_period_start'] = $data['job_period_start_hide'] ? $data['job_period_start_hide'] : '0000-01-01';
            $data['job_period_end'] = $data['job_period_end_hide'] ? $data['job_period_end_hide'] : '0000-01-01';
            //$data['prefecture'] = (int)$data['prefecture'];

            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorItem') && $this->validatorItem($data, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }

//            if (!$data['position']) {
            if (true) {
                $working_form = explode(',', $data['working_form']);
                $working_form_str = "";
                foreach ($working_form as $v) {
                    $working_form_str .= $resumeInfo['working_form'][$v] . "/";
                }
                $working_form_str = mb_substr($working_form_str, 0, mb_strlen($working_form_str)-1);

                $prefecture = "";
                if (isset($data['prefecture']) && $data['prefecture']) {
                    $prefectureArr = explode(',', $data['prefecture']);
                    foreach ($prefectureArr as $v) {
                        if ($v == 99) {
                            $prefecture .= $data['prefecture_other'] . "/";
                        } else {
                            $prefecture .= $resumeInfo['country_city'][$v] . "/";
                        }
                    }
                }
                $prefecture = mb_substr($prefecture, 0, mb_strlen($prefecture)-1);
                if ($prefecture) {
                    $prefecture = "・" . $prefecture;
                }

                $en_level = "";
                if (isset($data['en_level']) && $data['en_level']) {
                    $en_level = "、英語" . $resumeInfo['en_level'][$data['en_level']];
                }

                $nationality = "";
                if ($data['nationality_other']) {
                    $nationality = $data['nationality_other'] . "籍大歓迎。";
                }

                $yearly_income_up = $data['yearly_income_up'] != 9999 ? $resumeInfo["wage_arr_1"][$data['yearly_income_up']] : '';
                $yearly_income_low = $data['yearly_income_low'] != 9999 ? $resumeInfo["wage_arr_1"][$data['yearly_income_low']] : '';
                $yearly_income_str = '';
                if ($yearly_income_up || $yearly_income_low) {
                    $yearly_income_str = "年俸{$yearly_income_low}〜{$yearly_income_up}、";
                } else {
                    $monthly_income_up = $data['monthly_income_up'] != 9999 ? $resumeInfo["wage_arr_2"][$data['monthly_income_up']] : '';
                    $monthly_income_low = $data['monthly_income_low'] != 9999 ? $resumeInfo["wage_arr_2"][$data['monthly_income_low']] : '';
                    if ($monthly_income_up || $monthly_income_low) {
                        $yearly_income_str = "月給{$monthly_income_low}〜{$monthly_income_up}、";
                    } else {
                        $hourly_from = $data['hourly_from'] != 9999 ? $resumeInfo["wage_arr_3"][$data['hourly_from']] : '';
                        $hourly_to = $data['hourly_to'] != 9999 ? $resumeInfo["wage_arr_3"][$data['hourly_to']] : '';
                        if ($hourly_from || $hourly_to) {
                            $yearly_income_str = "時給{$hourly_from}〜{$hourly_to}、";
                        }
                    }
                }

                $data['position'] = "【{$working_form_str}{$prefecture}】{$nationality}{$data['job_name']}、{$yearly_income_str}日本語{$resumeInfo['jp_level'][$data['jp_level']]}{$resumeInfo['jp_level_2'][$data['jp_level_2']]}{$en_level}";
            }

            $data['employment_overseas'] = $data['employment_overseas'] ? $data['employment_overseas'] : 0;
            $data['working_visa'] = $data['working_visa'] ? $data['working_visa'] : 0;
            //保存商品

            $data['company_id'] = $this->user->id;
            $data['job_status'] = 0;
            $result = $this->model->saveItem($data);
            //save success
            if($result === false) {
                return response()->json([
                    'status' => 500,
                    'msg' => config('code.alert_msg.job.save_failed'),
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'msg' => config('code.alert_msg.job.save_success'),
                ]);
            }
        }
        return view('company.' . $this->viewName . '.input', ['data' => $data, 'isLevelPage' => 1]);
    }

    function copy(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->only(['job_id']);

            $job = $this->model->find($data['job_id']);
            if (!$job || $job['company_id'] != $this->user->id) {
                return response()->json([
                    'status' => 400,
                    'msg' => config('code.alert_msg.system.error'),
                ]);
            }

            $job['job_name'] = 'COPY_' . $job['job_name'];
            $job['job_status'] = 1;
            unset($job['job_id'], $job['fav_count'], $job['record_count'], $job['scout_count']);

            $id = $this->model->saveItem($job);

            if ($id === false) {
                $rs = [
                    'status' => 400,
                    'msg' => config('code.alert_msg.system.error'),
                ];
            } else {
                $rs = [
                    'status' => 200,
                    'msg' => config('code.alert_msg.job.copy_success'),
                    'url' => route('company.job.input', [$id]),
                ];
            }

            return response()->json($rs);
        }
    }

    public function deleted(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->only(['job_id']);

            $job = $this->model->find($data['job_id']);
            if (!$job || $job['company_id'] != $this->user->id) {
                return response()->json([
                    'status' => 400,
                    'msg' => config('code.alert_msg.system.error'),
                ]);
            }

            $job['deleted'] = 1;

            $id = $this->model->saveItem($job);

            if ($id === false) {
                $rs = [
                    'status' => 400,
                    'msg' => config('code.alert_msg.job.delete_failed'),
                ];
            } else {
                $rs = [
                    'status' => 200,
                    'msg' => config('code.alert_msg.job.delete_success'),
                ];
            }

            return response()->json($rs);
        }
    }
}
