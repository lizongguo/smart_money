<?php
/**
 * Created by NetBeans.
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserInfo;
use DB;

class UsersController extends BaseController
{
    public function __construct(Request $request, Users $model) {
        parent::__construct();
        $this->model = $model;
        view()->share('infos', UserInfo::select('id', 'name')->get());
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function parseSearch($data) {
        $sh = $data;
        $sh['role'] = 2; //用户
        if (!empty($data['username'])) {
            $sh['username'] = ['conn' => 'llk', 'value' => $data['username']];
        }
        if (!empty($data['phone'])) {
            $sh['phone'] = ['conn' => 'llk', 'value' => $data['phone']];
        }
        return $sh;
    }

    function founduser()
    {
        return view('admin.' . $this->viewName . '.founduser');
    }

    function founditems(Request $request)
    {
        $limit = $request->input('limit', 30);
        $page = $request->input('page', 1);
        $offset = ($page-1)*$limit;
        $sh = $request->input('sh', []);
        $obj = DB::table('users')->select('users.*');
        $obj->where('users.deleted', 0);
        if ($sh['name']) {
            $obj->where('name', 'like', '%'.$sh['name'].'%');
        }
        $count = count($obj->get()->toArray());
        $data = $obj->offset($offset)->limit($limit)->get()->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $data,
        ]);
    }

    function items(Request $request)
    {
        $limit = $request->input('limit', 30);
        $page = $request->input('page', 1);
        $offset = ($page-1)*$limit;
        $sh = $request->input('sh', []);
        $obj = DB::table('users')->select('users.*', 'user_info.name', 'user_info.phone', 'user_info.email as tzemail', 'user_info.address');
        $obj->join('user_info', 'user_info.user_id', '=', 'users.user_id');
        $obj->where('users.deleted', 0);
        if ($sh['name']) {
            $obj->where('user_info.name', 'like', '%'.$sh['name'].'%');
        }
        $count = count($obj->get()->toArray());
        $data = $obj->offset($offset)->limit($limit)->get()->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $data,
        ]);
    }

    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'email' => "required|unique:users,email,{$data['id']},id",
            'password' => 'nullable|regex:/^\w{6,12}$/i',
        ];
        $tips = [
            'email.required' => '账号不能为空',
            'email.unique' => '账号已经存在，请更换信息',
            'password.required' => '密码不能为空',
            'password.regex' => '密码格式不正确',
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
        if($id > 0 && $item = $this->model->getUserById($id)) {
            $data = $item;
            $data->id = $id;
            $data->permissions = explode(',', $data->permissions);
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
            $result = $this->model->saveUser($data);
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
