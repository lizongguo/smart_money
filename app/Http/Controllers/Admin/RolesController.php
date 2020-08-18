<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\Permissions;

class RolesController extends BaseController
{
    protected $model = null;

    public function __construct(Roles $model) {
        $this->model = $model;
        parent::__construct();
        //获取全部的权限列表
        view()->share('permissions', Permissions::select('id', 'name', 'slug')->get());
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['name'])) {
            $sh['name'] = ['conn' => 'llk', 'value' => $data['name']];
        }
        if (!empty($data['slug'])) {
            $sh['slug'] = ['conn' => 'llk', 'value' => $data['slug']];
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
            'name' => 'required',
            'slug' => 'required|regex:#^[\S]{4,}$#',
        ];
        $tips = [
            'name.required' => '角色名称为必填项',
            'slug.required' => '角色标示为必填项',
            'slug.regex' => '角色标示格式输入错误',
        ];
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['error'] = $validator->errors()->all();
            return false;
        }
        return true;
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function input(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->find($id)) {
            $data = $item;
            $data->id = $id;
            $permissionArr = [];
//            dd($data->permissions);
            foreach($data->permissions as $permission) {
                $permissionArr[$permission->id] = $permission->name;
            }
            $data->permission = $permissionArr;
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
