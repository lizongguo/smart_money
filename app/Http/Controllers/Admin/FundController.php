<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Fund;
use App\Imports\TestImport;
use App\Exports\TatentExport;
use Illuminate\Support\Facades\Auth;
use App\Models\UserFound;
use DB;

class FundController extends BaseController
{
    public function __construct(Request $request, Fund $model) {
        parent::__construct();
        $this->model = $model;
        $this->userFound = $userFound;
    }
    
    function items(Request $request) {
        $limit = $request->input('limit', 10);
        $sh = $request->input('sh', []);
        $page = $request->input('page', 1);
        $offset = ($page-1)*$limit;
        $obj = $this->model->select('fund.*');
        $obj->where('fund.deleted', 0);
        if ($sh['name']) {
            $obj->where('name', 'like', '%'.$sh['name'].'%');
        }
        $total = count($obj->get());
        $data = $obj->offset($offset)->limit($limit)->get()->toArray();
        $fund_type = config('code.fund_type');
        foreach ($data as $k => &$v) {
            $v['fund_type'] = $fund_type[$v['type']];
            if (!$v['code']) {
                $v['code'] = '暂无';
            }
            if (!$v['ranking']) {
                $v['ranking'] = '暂无';
            }
        }
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => $total,
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
            'name' => "required|unique:fund,name,{$data['id']},id",
        ];
        $tips = [
            'name.required' => '基金名称不能为空',
            'name.unique' => '基金已经存在，请勿重复添加',
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
            $data['ranking'] = (int)$data['ranking'];
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
}
