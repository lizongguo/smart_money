<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admins;
use App\Models\Roles;
use App\Models\Permissions;

class AdminsController extends BaseController
{
    protected $model = null;

    public function __construct(Admins $model) {
        $this->model = $model;
        parent::__construct();
        //获取全部的权限列表
        view()->share('roles', Roles::select('id', 'name', 'slug')->get());
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
            //'phone' => "required|regex:#^1[\d]{10}$#|unique:admins,phone,{$data['id']},id",
            'email' => "required|email|unique:admins,email,{$data['id']},id",
            'role' => 'required',
            'remarks' => 'max:255',
        ];
        $tips = [
            'name.required' => '用户名为必填项',
            'email.unique' => '邮箱已经注册，请更换邮箱',
            //'phone.unique' => '手机号已经注册，请更换手机号',
            //'phone.required' => '手机号不能为空',
            //'phone.regex' => '手机号格式输入错误',
            'role.required' => '角色不能为空',
            'email.required' => '邮箱为必填项',
            'email.email' => '请输入正确的邮箱地址',
            'remarks.max' => '备注长度不能超过255个字',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }
    
}
