<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FoundCapital;
use Illuminate\Support\Facades\Auth;

use App\Models\Users;

class FoundCapitalController extends BaseController
{
    public function __construct(Request $request, FoundCapital $model) {
        parent::__construct();
        $this->model = $model;
        view()->share('users', Users::select('user_id', 'name')->get());
    }

    function index(Request $request, $found_no)
    {
        return view('admin.' . $this->viewName . '.index',['found_no' => $found_no]);
    }

    function item(Request $request)
    {
        $sh = $request->all();
        $items = $this->model
                ->select('found_capital.*', 'users.name as uname')
                ->leftjoin('users', 'users.user_id', '=', 'found_capital.user_id')
                ->where('found_capital.deleted', '0')
                ->where('found_capital.fund_id', $sh['found_no'])
                ->get()
                ->toArray();
        foreach ($items as $k => &$v) {
            if ($v['type']==1) {
                $v['type_val'] = '个人';
            } else {
                $v['type_val'] = '公司';
                $v['uname'] = $v['name'];
            }
        }
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => count($items),
            'data' => $items,
        ]);
    }

    protected function validatorItem($data, &$msg) {
        if ($data['type']==1) {
            $valid = [
                'user_id' => 'required',
                'amount_cn' => 'required|numeric',
                'amount_us' => 'required|numeric',
            ];
        } else {
            $valid = [
                'name' => 'required',
                'amount_cn' => 'required|numeric',
                'amount_us' => 'required|numeric',
            ];
        }
        $tips = [
            'amount_cn.numeric' => '认缴金额(人民币)格式不正确',
            'amount_us.numeric' => '认缴金额(美元)格式不正确',
            'user_id.required' => '请选择出资人',
            'name.required' => '请输入公司名称',
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
        $type = 0;
        if($id > 0 && $item = $this->model->getOne($id)) {
            $data = $item;
            $data->id = $id;
            $type = $data->type;
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
        return view('admin.' . $this->viewName . '.input', ['found_no' => $param['found_no'], 'data' => $data, 'type' => $type]);
    }

}
