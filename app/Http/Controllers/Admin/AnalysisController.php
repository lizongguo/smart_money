<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FundStock;
use Illuminate\Support\Facades\Auth;
use DB;

class AnalysisController extends BaseController
{
    public function __construct(Request $request, FundStock $model) {
        parent::__construct();
        $this->model = $model;
    }
    
    function items(Request $request) {
        $limit = $request->input('limit', 10);
        $sh = $request->input('sh', []);
        $page = $request->input('page', 1);
        $offset = ($page-1)*$limit;
        $obj = $this->model->select('*');
        $obj->where('deleted', 0);
        if ($sh['name']) {
            $obj->where('name', 'like', '%'.$sh['name'].'%');
        }
        $total = count($obj->get());
        $data = $obj->offset($offset)->limit($limit)->get()->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => $total,
            'data' => $data,
        ]);
    }

    protected function validatorItem($data, &$msg) {
        $valid = [
            'name' => "required|unique:stock,name,{$data['id']},id",
            'code' => "required|unique:stock,code,{$data['id']},id",
        ];
        $tips = [
            'name.required' => '股票名称不能为空',
            'name.unique' => '请勿重复添加',
            'code.required' => '股票代码不能为空',
            'code.unique' => '请勿重复添加',
        ];
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }

    function input(Request $request, $id = 0) {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->getFoundById($id)) {
            $data = $item;
            $data->id = $id;
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
            if($result === false) {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存失败',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'msg' => '保存成功'
                ]);
            }
        }
        return view('admin.' . $this->viewName . '.input', ['data' => $data]);
    }
}
