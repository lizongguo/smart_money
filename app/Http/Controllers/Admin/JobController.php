<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

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

        view()->share('company', Company::select('id', 'company_name')->where('deleted', 0)->get());
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
            $sh['position'] = ['conn' => 'lk', 'value' => str_replace(['\\', '%', '_'], ['\\'.'\\', '\\'.'%', '\\'.'_'], $data['position'])];
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
        $valid = [
            'job_name' => 'required',
        ];
        
        $tips = [
            'job_name.required' => '菜品名称为必填项',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }


    function updateIncome($key, $data) {
        if (!isset($data[$key])) {
            $temK = 0;
            foreach ($data as $k => $v) {
                if ($key <= $k) {
                    break;
                }
                $temK = $k;
            }

            return $temK;
        } else {
            return $key;
        }
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
            $data = $item;
            $data->job_id = $id;
            $data->yearly_income_low = $this->updateIncome($data->yearly_income_low, $resumeInfo['wage_arr_1']);
            $data->yearly_income_up = $this->updateIncome($data->yearly_income_up, $resumeInfo['wage_arr_1']);
            $data->monthly_income_low = $this->updateIncome($data->monthly_income_low, $resumeInfo['wage_arr_2']);
            $data->monthly_income_up = $this->updateIncome($data->monthly_income_up, $resumeInfo['wage_arr_2']);

            $data->job_period_end = $data->job_period_end != "0000-01-01" ? $data->job_period_end : "";
            $data->job_period_start = $data->job_period_start != "0000-01-01" ? $data->job_period_start : "";
        }
        if ($request->isMethod('post')) {
            $data = $request->input('data');

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
            $data['job_period_start'] = $data['job_period_start'] ? $data['job_period_start'] : '0000-01-01';
            $data['job_period_end'] = $data['job_period_end'] ? $data['job_period_end'] : '0000-01-01';
            //$data['prefecture'] = (int)$data['prefecture'];

            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorItem') && $this->validatorItem($data, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }

            if (!$data['position']) {
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
                }

                $data['position'] = "【{$working_form_str}{$prefecture}】{$nationality}{$data['job_name']}、{$yearly_income_str}日本語{$resumeInfo['jp_level'][$data['jp_level']]}{$resumeInfo['jp_level_2'][$data['jp_level_2']]}{$en_level}";

            }

            $data['employment_overseas'] = $data['employment_overseas'] ? $data['employment_overseas'] : 0;
            $data['working_visa'] = $data['working_visa'] ? $data['working_visa'] : 0;
            //保存商品

            $data['job_status'] = 0;
            $result = $this->model->saveItem($data);
            //save success
            if($result === false) {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存に失敗しました。',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'msg' => '保存に成功しました。'
                ]);
            }
        }
        return view('admin.' . $this->viewName . '.input', ['data' => $data]);
    }
    

    /**
     * 店铺设置
     */
    public function setting(Request $request, Setting $settingModel, $id) {
        
        $shop = $this->model->getOne(intval($id));
        if (!$shop) {
            //店铺不存在.
            return redirect()->back();
        }
        
        $data = $settingModel->whereExtend(['shop_id' => $shop->id, 'order' => ['field' => 'category', 'sort' => 'ASC']])->pluck('value', 'name');
        if ($request->isMethod('post')) {
            $post = $request->input('data');
            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorSettingItem') && $this->validatorSettingItem($post, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }
            $result = $settingModel->saveShopOptions($post, $shop->id);
            
            //save success
            if ($result) {
                return response()->json([
                    'status' => 200,
                    'msg' => '保存に成功しました。'
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存に失敗しました。',
                    'data' => $data
                ]);
            }
        }
        
        return view('admin.' . $this->viewName . '.setting', ['data' => $data]);
    }
}
