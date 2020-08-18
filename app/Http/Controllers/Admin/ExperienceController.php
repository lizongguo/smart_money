<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01 
 * Time: 15:01
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Experience;
use App\Models\Roles;
use Validator;

class ExperienceController extends BaseController
{

    public function __construct(Experience $model) {
        parent::__construct();
        $this->model = $model;

        view()->share('jobInfo', config("code.job"));
        view()->share('resumeInfo', config("code.resume"));
        view()->share('companyInfo', config("code.company"));
        view()->share('pageTitle', $this->pageTitle);
    }

    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['name'])) {
            $sh['name'] = ['conn' => 'orlike', 'filed' => ["name"], 'value' => str_replace(['\\', '%', '_'], ['\\'.'\\', '\\'.'%', '\\'.'_'], $data['name'])];
        }

        if (!empty($data['created_start']) || !empty($data['created_end'])) {
            $data['created_start'] = $data['created_start'] ? $data['created_start'] : "2000-01-01";
            $data['created_end'] = $data['created_end'] ? $data['created_end'] : "2099-01-01";
            $sh['created_at'] = ['conn' => 'between', 'value' => [$data['created_start'], $data['created_end']]];
        }

        unset($sh['created_start'], $sh['created_end']);

        return $sh;
    }

    function items(Request $request) {
        $rs = parent::items($request, true);
        $resumeInfo = config("code.resume");

        foreach ($rs['data'] as &$v) {
            $v = Experience::dealItemData($v);
        }
        return response()->json($rs);
    }

    public function detail(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->find($id)) {
            $data = Experience::dealItemData($item);
        }

        $item = $this->model->find($id);

        return view('admin.' . $this->viewName . '.detail', ['data' => $data]);
    }

    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'recommendation' => "required|max:1000",
        ];
        $tips = [
            'recommendation.required' => '推薦文は空にできません。',
            'recommendation.max' => '推薦文は1000文字を超えることはできません。',
        ];

        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function input(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->find($id)) {
            $data = Experience::dealItemData($item);
        }

        if ($request->isMethod('post')) {
            $data = $request->input('data');
            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorItem') && $this->validatorItem($data, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }
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

    
}
