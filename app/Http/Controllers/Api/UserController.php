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
use Illuminate\Support\Facades\Cookie;
use App\Http\Models\UserIdentity;
use App\Http\Models\ShopWaiter;
use Validator;

class UserController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * 小程序登录
     * @param Request $request
     */
    public function login(Request $request) {
        $code = $request->header('code', null);
        
        if (!preg_match('#^[\d\w\-\_\.]{10,40}$#i', $code)) {
            $this->back['status'] = '400';
            $this->back['msg'] = "CODE 请求参数有误";
            return $this->dataToJson($this->back);
        }
        
        $openid = $this->userModel->getOpenID($code, $request->header('device-type', null), $this->back);
        
        if($openid === false) {
            return $this->dataToJson($this->back);
        }
        
        $user = $this->userModel->getUserByOpenid($openid, $request->header('device-type', null), $this->back);
        if($user === false) {
            $this->back['data'] = [
                'open_id' => $openid
            ];
            return $this->dataToJson($this->back);
        }
        
        $this->back['data'] = $user;
        return $this->back;
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function register(Request $request) {
        $data = $request->all();
        $data['open_id'] = $request->header('open-id', null);
        $data['device_type'] = $request->header('device_type', null);
        $valid = [
            'open_id' => 'required',
            'phone' => 'regex:#^1[\d]{10}$#',
            'verificationCode' => 'regex:#^[\d]{4}$#',
            'username' => 'required',
            'avatar' => 'required',
        ];
        $notice = [
            'open_id.required' => 'openid输入有误。', 
            'phone.regex' => '手机号输入有误。',
            'verificationCode.regex' => '手机验证码输入有误。',
            'username.required' => '用户名不能为空。', 
            'avatar.required' => '用户头像不能为空。', 
        ];
        
        $validator = Validator::make($data, $valid, $notice);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        //验证码验证
        if (!$this->userModel->checkCode($data['phone'], $data['verificationCode'], 'register')) {
            $this->back['status'] = '400';
            $this->back['msg'] = "手机验证码输入不正确，或者已过期。";
            return $this->dataToJson($this->back);
        }
        
        //注册或绑定现有用户
        $user = $this->userModel->register($data, $this->back);
        
        if ($user === false) {
            return $this->dataToJson($this->back);
        }
        
        $this->back['data'] = $user;
        return $this->back;
    }
    
    
    /**
     * 用户情报编辑
     * @param Request $request
     * @return type
     */
    public function edit(Request $request)
    {
        //判断登录是否正常
        $flag = $this->authenticate();
        if ($flag !== true) {
            return $flag;
        }
        $data = $request->all();
        if (empty($data['avatar']) && empty($data['username'])) {
            $this->back['status'] = '400';
            $this->back['msg'] = "用户名和头像不能同时为空。";
            return $this->dataToJson($this->back);
        }
        
        $valid = [
            'avatar' => ['nullable', 'regex:#^\/upload(.*)\.(png|jpeg|jpg|gif)#i'],
            'username' => 'nullable|max:20'
        ];
        
        $notice = [
            'avatar.regex' => '用户头像格式错误。',
            'username.max' => '用户名最大长度为20个字符。'
        ];
        
        $validator = Validator::make($data, $valid, $notice);
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $data['id'] = $this->user['id'];
        
        //更新用户情报
        $rs = $this->userModel->saveItem($data);
        
        if ($rs === false) {
            $this->back['status'] = '500';
            $this->back['msg'] = '系统错误，请稍后再试。';
            return $this->dataToJson($this->back);
        }
        $user = $this->userModel->getUserById($this->user['id']);
        $this->back['data'] = $user;
        return $this->back;
    }
    
    
    /**
     * 退出登录
     * @param Request $request
     */
    public function logout(Request $request) {
        $rs = $this->authenticate();
        if($rs !== true) {
            return $rs;
        }
        $tokenModel = new \App\Models\UserTokens();
        $res = $tokenModel->logout($this->user, $request->header('device-type', ''));
        
        return $this->back;
    }
    
    /**
     * 
     * @param Request $request
     */
    public function center(Request $request) {
        $rs = $this->authenticate();
        if($rs !== true) {
            return $rs;
        }
        //获取预约数量
        $bookingModel = new \App\Models\Booking;
        $booking_num = $bookingModel->getBookingNum($this->user['id']);
        $booking_wait_num = $bookingModel->getBookingWaitNum($this->user['id']);
        
        //获取地址数
        $addressModel = new \App\Models\Address();
        $address_num = $addressModel->getAddressNum($this->user['id']);
        
        $this->user['booking_num'] = $booking_num;
        $this->user['address_num'] = $address_num;
        $this->user['booking_wait_num'] = $booking_wait_num;
        
        $this->back['data'] = $this->user;
        return $this->back;
        
    }
}
