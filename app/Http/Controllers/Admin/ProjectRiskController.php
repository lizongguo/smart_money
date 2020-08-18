<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProjectRisk;
use Illuminate\Support\Facades\Auth;

class ProjectRiskController extends BaseController
{
    public function __construct(Request $request, ProjectRisk $model) {
        parent::__construct();
        $this->model = $model;
    }

    function index(Request $request, $project_no)
    {
        return view('admin.' . $this->viewName . '.index',['project_no' => $project_no]);
    }

    function item(Request $request)
    {
        $sh = $request->all();
        $items = $this->model
                ->select('*')
                ->where('deleted', '0')
                ->where('fund_id', $sh['project_no'])
                ->get()
                ->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => count($items),
            'data' => $items,
        ]);
    }

    protected function validatorItem($data, &$msg) {
        if ($data['content_type']==1) {
            $valid = [
                'name' => "required",
                'path' => 'required',
            ];
        } else {
            $valid = [
                'name' => "required",
                'content' => 'required',
            ];
        }
        $tips = [
            'name.required' => '名称不能为空',
            'path.required' => '请上传文件',
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
        $param = $request->all();
        $id = (int)$id;
        if($id > 0 && $item = $this->model->getOne($id)) {
            $data = $item;
            $data->id = $id;
            $content_type=$data->content_type;
        } else {
            $content_type=1;
        }
        if ($request->isMethod('post')) {
            $data = $request->input('data');
            if (method_exists($this, 'validatorItem') && $this->validatorItem($data, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }
            $result = $this->model->saveFinancial($data);
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
        return view('admin.' . $this->viewName . '.input', ['project_no' => $param['project_no'], 'data' => $data, 'content_type' => $content_type]);
    }

}
