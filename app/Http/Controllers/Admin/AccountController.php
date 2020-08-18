<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01 
 * Time: 15:01
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admins;
use App\Models\Roles;
use Validator;

class AccountController extends BaseController
{
    public $currentMenu = 'info|account.index';
    public $pageTitle = '基本资料';
    public function __construct(Admins $model) {
        parent::__construct();
        $this->model = $model;
        
        view()->share('pageTitle', $this->pageTitle);
    }
    
    /**
     * 基本资料
     * @param Request $request
     * @return type
     */
    public function index(Request $request) {
        $data = $this->model->find($this->user['id']);
        $rolesModel = new Roles();
        $roles = $rolesModel->pluck('name', 'id')->toArray();
        if ($request->isMethod('post')) {
            $data = $request->input('data');
            $validator = Validator::make($data, [
                'name' => [
                    'required',
                ],
//                'phone' => [
//                    'required',
//                    'regex:#^1[\d]{10}$#',
//                    "unique:admins,phone,{$this->user['id']},id"
//                ],
                'email' => [
                    'required',
                    'email',
                    "unique:admins,email,{$this->user['id']},id"
                ],
                'remarks' => [
                    'max:255'
                ],
            ],
            [
                'name.required' => '用户名为必填项',
                'email.unique' => '邮箱已经注册，请更换邮箱',
                //'phone.unique' => '手机号已经注册，请更换手机号',
                //'phone.required' => '手机号不能为空',
                //'phone.regex' => '手机号格式输入错误',
                'email.required' => '邮箱为必填项',
                'email.email' => '请输入正确的邮箱地址',
                'remarks.max' => '备注长度不能超过255个字',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => $validator->errors()->all(),
                ]);
            }
            $data['id'] = $this->user['id'];
            $result = $this->model->saveItem($data);
            //save success
            if($result !== false) {
                $this->model->find($this->user['id']);
                $request->session()->put('backUser', $this->model->find($this->user['id'])->toArray());
                return $this->dataToJson(['status' => 200, 'msg' => '个人资料保存成功']);
            }
            return $this->dataToJson(['status' => 500, 'msg' => '个人资料保存失败，请稍后再试。']);
        }
        return view('admin.' . $this->viewName . '.info', ['data' => $data, 'roles' => $roles]);
    }
    
    /**
     * 修改密码
     * @param Request $request
     * @return type
     */
    public function changePwd(Request $request)
    {
        $id = $this->user['id'];
        $item = $this->model->find($id);
        
        if ($request->isMethod('post')) {
            $data = $request->input('data');
            $validator = Validator::make($data, [
                'pwd' => [
                    'required',
                    'regex:#^[\d\w\_]{6,12}$#is'
                ],
                'pwd1' => [
                    'required',
                    'regex:#^[\d\w\_]{6,12}$#is'
                ],
                'pwd2' => [
                    'required',
                    'same:pwd1'
                ],
            ]);
            if ($validator->fails()) {
                $this->back['status'] = '400';
                $this->back['msg'] = '参数有误。';
                return $this->dataToJson($this->back);
            }
            
            if(!\Hash::check($data['pwd'], $item->password)){
                return $this->dataToJson([
                    'status' => 410,
                    'msg' => '当前密码输入不匹配',
                ]);
            }
            
            $result = $this->model->where('id', $item->id)->update(['password' => \Hash::make($data['pwd1'])]);
            
            //save success
            if($result !== false) {
                return $this->dataToJson(['status' => 200, 'msg' => '密码修改成功。']);
            }
            return $this->dataToJson(['status' => 400, 'msg' => '密码修改失败了，请稍后重试。']);
        }
        return view('admin.' . $this->viewName . '.changePwd', ['data' => $data, 'currentMenu' => $this->currentMenu]);
    }
    
}
