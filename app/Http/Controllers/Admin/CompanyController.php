<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends BaseController
{
    public function __construct(Request $request, Company $model) {
        parent::__construct();
        $this->model = $model;

        view()->share('jobInfo', config("code.company"));
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['company_name'])) {
            $sh['company_name'] = ['conn' => 'lk', 'value' => str_replace(['\\', '%', '_'], ['\\'.'\\', '\\'.'%', '\\'.'_'], $data['company_name'])];
        }
        
        if (!empty($data['email'])) {
            $sh['email'] = ['conn' => '=', 'value' => $data['email']];
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
            'company_name' => 'required',
            'type' => 'required',
            'email' => "required|email|unique:company,email,{$data['id']},id",
            'resume_count' => 'required',
        ];
        $tips = [
            'company_name.required' => '企業名は空にできません。',
            'type.required' => 'タイプは空にできません。',
            'email.required' => 'メールは空にできません。',
            'email.unique' => 'メールはすでに存在します',
            'resume_count.required' => '履歴書の数は空にできません。',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }

    function items(Request $request) {
        $rs = parent::items($request, true);
        $resumeInfo = config("code.company");

        foreach ($rs['data'] as &$v) {
            $v['type'] = $resumeInfo['company_type'][$v['type']];
        }

        return response()->json($rs);
    }

}
