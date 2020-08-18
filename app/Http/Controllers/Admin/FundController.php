<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Found;
use App\Imports\TestImport;
use App\Exports\TatentExport;
use Illuminate\Support\Facades\Auth;
use App\Models\UserFound;
use Excel;
use DB;

use App\Libraries\BravePHPExcel;

class FoundController extends BaseController
{
    public function __construct(Request $request, Found $model, UserFound $userFound) {
        parent::__construct();
        $this->model = $model;
        $this->userFound = $userFound;
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['goods_name'])) {
            $sh['goods_name'] = ['conn' => 'lk', 'value' => $data['goods_name']];
        }
        return $sh;
    }

    function items(Request $request) {
        $limit = $request->input('limit', 10);
        $sh = $request->input('sh', []);
        $obj = $this->model->select('found.*');
        $obj->where('found.deleted', 0);
        if ($sh['current_name']) {
            $obj->where('current_name', 'like', '%'.$sh['current_name'].'%');
        }
        $data = $obj->limit($limit)->get()->toArray();
        foreach ($data as $k => &$v) {
            $v['total_val'] = $this->getFoundVal($v['found_no']);
        }
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => count($data),
            'data' => $data,
        ]);
    }

    private function getFoundVal($id)
    {
        $projects = $this->userFound->getProject($id);
        $total_val = 0;
        foreach ($projects as $k => $v) {
            $v->tz_rate = ($v->rate)/100;
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
            } else {
                $v->cur_val = $v->amount_cn * $v->tz_rate;
            }
            $total_val+=$v->cur_val;
        }
        return round($total_val, 2);
    }

    function project(Request $request) {
        $id = $request->input('id', 0);
        $item = $this->model->where('deleted', 0)->find($id);
        $infos = DB::table('project')
                ->select('project.*')
                ->join('found_project', 'found_project.project_id', '=', 'project.project_no')
                ->where('project.deleted', '0')
                ->where('found_project.fund_id', $id)
                ->get()
                ->toArray();
        return view('admin.' . $this->viewName . '.project', ['data' => $item, 'infos' => $infos]);
    }

    protected function validatorItem($data, &$msg) {
        $valid = [
            'current_name' => "required",
            'total_value_cn' => 'required|numeric',
        ];
        $tips = [
            'current_name.required' => '基金名称不能为空',
            'total_value_cn.required' => '基金总额不能为空',
            'total_value_cn.numeric' => '请输入正确的金额',
        ];
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }

    function financial(Request $request, $found_no)
    {
        return view('admin.' . $this->viewName . '.financial',['found_no' => $found_no]);
    }

    //编辑基金信息
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
            $result = $this->model->saveFoundData($data);
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

    function finitem(Request $request)
    {
        $sh = $request->all();
        $items = DB::table('found_financial')
                ->select('found_financial.*')
                ->where('found_financial.deleted', '0')
                ->where('found_financial.fund_id', $sh['found_no'])
                ->get()
                ->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => count($items),
            'data' => $items,
        ]);
    }

    function insert(Request $request) {
        //基金信息
        /*$filePath = base_path('storage/data/found.xlsx');
        $array = Excel::toArray(new TestImport, $filePath);
        $this->model->saveFound($array[0]);*/
        //用户信息
        /*$filePath = base_path('storage/data/user_info.xlsx');
        $array = Excel::toArray(new TestImport, $filePath);
        $this->model->saveUser($array[0]);*/
        //基金项目信息
        /*$filePath = base_path('storage/data/found_project.xlsx');
        $array = Excel::toArray(new TestImport, $filePath);
        $this->model->saveFroject($array[0]);*/
        //用户基金信息
        /*$filePath = base_path('storage/data/user_found.xlsx');
        $array = Excel::toArray(new TestImport, $filePath);
        $this->model->saveUserFound($array[0]);*/
       
        return Excel::download(new TatentExport($row,$list, 2020), date('Y:m:d ') . 'test.xlsx');
    }

}
