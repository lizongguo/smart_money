<?php
/**
 * upload Controller
 *
 * @package       Api.Controller
 * @author        lee
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) bravesoft Inc.
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Waiter;
use Validator;

class WaiterController extends BaseController
{
    protected $waiter = null;
    public function __construct(Request $request,Waiter $model)
    {
        $this->waiter = $model;
        parent::__construct($request);
    }
    
    /**
     * 员工登录
     * @param Request $request
     */
    public function login(Request $request) {
        $data = $request->all();
        $data['device_type'] = $request->header('device-type', null);
        $valid = [
            'phone' => 'regex:#^1[\d]{10}$#',
            'password' => 'regex:#^[\d\w\_]{6,12}$#is',
            'device_token' => 'required',
            'device_type'  => 'in:android,ios'
        ];
        
        $notice = [
            'device_type.in' => '设备类型输入有误。', 
            'phone.regex' => '手机号输入有误。',
            'password.regex' => '密码长度为6到12英数组合。',
            'device_token.require' => '推送token不能为空。',
        ];
        $validator = Validator::make($data, $valid, $notice);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $user = $this->userModel->waiterLogin($data['phone'], $data['password'], $data['device_token'], $data['device_type'], $this->back);
        if ($user == false) {
            return $this->dataToJson($this->back);
        }
        
        $this->back['data'] = $user;
        return $this->back;
    }
    
    
    /**
     * 员工用户忘记密码
     * @param Request $request
     */
    public function forgetpwd(Request $request) {
        $data = $request->all();
        $data['device_type'] = $request->header('device-type', null);
        $valid = [
            'phone' => 'regex:#^1[\d]{10}$#',
            'password' => 'regex:#^[\d\w\_]{6,12}$#is',
            'device_type'  => 'in:android,ios',
            'verificationCode' => 'regex:#^[\d]{4}$#',
        ];
        $notice = [
            'device_type.in' => '设备类型输入有误。', 
            'phone.regex' => '手机号输入有误。',
            'password.regex' => '密码长度为6到12英数组合。',
            'verificationCode.require' => '手机验证码输入有误。',
        ];
        
        $validator = Validator::make($data, $valid, $notice);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        //验证码验证
        if (!$this->userModel->checkCode($data['phone'], $data['verificationCode'], 'forgetpwd')) {
            $this->back['status'] = '400';
            $this->back['msg'] = "手机验证码输入不正确，或者已过期。";
            return $this->dataToJson($this->back);
        }
        
        $rs = $this->userModel->resetPassword($data['phone'], $data['password'], $this->back);
        
        //删除redis中的验证码
        !!$rs ? $this->userModel->delRedisCode('forgetpwd', $data['phone']) : null;
        
        
        return $this->back;
    }
    
    
    
    
}
