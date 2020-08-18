<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Redis;
use App\Repositories\Wechat\SmallProgramApiRepository;
use App\Services\Aliyun\AlipayService;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

    /**
     * 通过id获取用户
     * @param type $id
     */
    public function getUserById($id)
    {
        $user = $this->select('*')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();
        if (!$user) {
            return false;
        }
        return $user;
    }
    
    /**
     * 删除用户redis中的登录缓存
     * @param type $access_token
     */
    public function delRedisUserData($access_token) 
    {
        //清除当前用户redis缓存。
        $redisKey = sprintf(config('rediskeys.user_access_hash'), $access_token);
        Redis::del($redisKey);
    }
    
    
    /**
     * 修改用户密码
     * @param type $user_id
     * @param type $opassword
     * @param type $password
     * @param type $error
     */
    public function changePassword($user_id, $opassword, $password, &$error){
        $user = $this->where('id', (int)$user_id)->where('deleted', 0)->first();
        if(!$user) {
            $error['status'] = '410';
            $error['msg'] = '用户不存在';
            return false;
        }
        if($user->password != $this->encryptionPassword($opassword, $user->sign)) {
            $error['status'] = '410';
            $error['msg'] = '原密码输入错误。';
            return false;
        }
        $rs = $this->where('id', $user_id)->update(['password' => $this->encryptionPassword($password, $user->sign)]);
        if($rs === false) {
            $error['status'] = '500';
            $error['msg'] = '密码修改失败。';
            return false;
        }
        return true;
    }
    
    /**
     * code 2 session
     * @param type $code
     * @param type $error
     * @return boolean
     */
    public function getOpenID($code, $device_type,  &$error = null) 
    {
        if (!in_array($device_type, ['wechat', 'alipay'])) {
            $error = [
                'status' => '400',
                'msg' => 'deviceType 参数输入有误'
            ];
            return false;
        }
        
        if ($device_type == 'wechat') {
            $service = app()->make("App\Repositories\Wechat\SmallProgramApiRepository");
            $result = $service->getOpenidByCode($code);
        } else {
            $service =  app()->make("App\Services\Aliyun\AlipayService");
            $result = $service->getAuthTokenByCode($code);
        }
        
        if ($result === false) {
            $error = [
                'status' => '510',
                'msg' => '获取openid失败，请稍后重试。'
            ];
            return false;
        }
        
        if ($device_type == 'wechat') {
            $openid = $result['openid'];
        } else {
            $openid = $result['user_id'];
        }
        
        return $openid;
    }
    
    /**
     * 通过openid 获取用户详细情报, 没有创建，已有就查询
     * @param type $openid
     * @param type $device_type
     * @param type $error
     * @return boolean
     */
    public function getUserByOpenid($openid, $device_type) {
        $condition = [];
        if ($device_type == 'wechat') {
            $condition['wx_open_id'] = $openid;
        } else {
            $condition['ali_open_id'] = $openid;
        }
        $item = $this->whereExtend($condition)->select('id')->first();
        
        $date = date('Y-m-d H:i:s');
        if(!$item) {
            //用户不存在直接返回openid
            return false;
        }else{
            //修改
            $saveData = [
                'last_login_at' => $date,
                'last_login_ip' => request()->getClientIp(),
                'id' => $item->id,
            ];
            //保存登录情报
            $this->saveItem($saveData);
        }
        
        //获取用户
        $user = $this->getUserById($item->id);
        
        //获取或者创建 access token
        $tokenModel = new UserTokens();
        $access_token = $tokenModel->getAccessTokenByUserIdAndDeviceType($user->id, $device_type);
        $user->access_token = $access_token;
        
        return $user;
    }
    
    
    /**
     * 小程序用户注册
     * @param type $data
     */
    public function register($data, &$error = null) {
        if (!isset($this->deviceType[$data['device_type']])) {
            $error = [
                'status' => '400',
                'msg' => '请求连接不存在。',
            ];
            return false;
        }
        $item = $this->where('phone', $data['phone'])
            ->where('role', 2)
            ->where('deleted', 0)
            ->select('id')->first();
        
        $date = date('Y-m-d H:i:s');
        
        $save = [
            'phone' => $data['phone'],
            'username' => $data['username'],
            'avatar' => $data['avatar'],
            'role' => 2,
            'state' => 1,
            'last_login_at' => $date,
            'last_login_ip' => request()->getClientIp(),
            $this->deviceType[$data['device_type']] => $data['open_id'],
            'deleted' => 0,
        ];
        if (!!$item) {
            $save['id'] = $item->id;
        }
        
        DB::beginTransaction();
        try {
            $id = $this->saveItem($save);
            //获取或者创建 access token
            $tokenModel = new UserTokens();
            $access_token = $tokenModel->getAccessTokenByUserIdAndDeviceType($id, $data['device_type']);
            
            DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            Log::error($ex);
            return false;
        }
        $user = $this->getUserById($id);
        $user->access_token = $access_token;
        
        return $user;
    }
    
    public function resetPassword($phone, $password, &$back = null)
    {
        $user = $this->select('id', 'username', 'password', 'role')
            ->where('phone', $phone)->where('deleted', 0)->where('state', 1)->where('role', 1)
            ->join('user_waiter', 'user_id', '=', 'users.id')
            ->first();
        
        if ($user === false) {
            $back = ['status' => '420', 'msg' => '用户不存在。'];
            return false;
        }
        
        //保存数据
        $save = [
            'id' => $user->id,
            'password' => \Hash::make($password)
        ];
        $rs = $this->saveItem($save);
        
        if (!$rs) {
            $back = ['status' => '420', 'msg' => '系统错误，请稍后再试。'];
            return false;
        }
        return true;
    }

    public function saveUser($data) {
        \DB::beginTransaction();
        try {
            $id = $data['id'];
            $update = [
                'email' => $data['email'],
                'note' => $data['note'],
                'email_info' => $data['email_info'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'tel_phone' => $data['tel_phone'],
                'recipient' => $data['recipient'],
                'permissions' => $data['permissions'],
            ];
            if (!empty($data['password'])) {
                $update['password'] = \Hash::make($data['password']);
            }
            $this->where('id', $id)->update($update);
            \DB::commit();
        }catch(\Illuminate\Database\QueryException $ex) {
            \DB::rollback();
            \Log::error($ex);
            return false;
        }
        return true;
    }
}
