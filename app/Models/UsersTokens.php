<?php

namespace App\Models;

use Illuminate\Support\Facades\Redis;

class UserTokens extends BaseModel
{
    protected $table = 'user_tokens';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    
    
    /**
     * 生成access token 字符串
     * @return string
     */
    public function getAccessToken($device_type = '') {
        return md5($device_type . uniqid('login' . microtime(true)));
    }
    
    /**
     * 通过userid 和device type生成 获取 access token
     * @param type $user_id
     * @param type $device_type
     * @return string 
     */
    public function getAccessTokenByUserIdAndDeviceType($user_id, $device_type) {
        $token = $this->select('id', 'access_token')
                ->where('user_id', $user_id)
                ->where('device_type', $device_type)
                ->first();
        if (!$token) {
            $token = [
                'access_token' => $this->getAccessToken($device_type),
                'user_id' => $user_id,
                'device_type' => $device_type
            ];
            $this->saveItem($token);
            $access_token = $token['access_token'];
        } else {
            $access_token = $token->access_token;
        }
        
        return $access_token;
    }
    
    
    /**
     * 
     * @param type $user
     * @param type $deviceType
     * @param string $back
     * @return boolean
     */
    public function logout ($user, $deviceType)
    {
        $res = $this->where('user_id', $user['id'])->where('device_type', $deviceType)->first();
        if (!$res) {
            return true;
        }
        
        $redisKey = sprintf(config('rediskeys.user_access_hash'), $res->access_token);
        Redis::del($redisKey);
        //如果是员工用户，还需清除 push token 和 device type
        if ($user['role'] == 1) {
            $waiterModel = new Waiter;
            $waiterModel->where('user_id', $res->user_id)->where('device_type', $deviceType)->update([
                'device_type' => null,
                'push_token' => '',
            ]);
        }
        
        //删除用户token
        $res->delete();
        
        return true;
    }
    
}
