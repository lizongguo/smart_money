<?php
namespace App\Repositories\Wechat;
use GuzzleHttp\Client;
use App\Libraries\BLogger;
use App\Libraries\BraveXml;
use App\Models\Setting;
    
/**
 * @class WechatPay
 * @brief 移动微信支付
 * @date 2018/9/25 15:45:40
 */
class SmallProgramApiRepository
{
    //支付插件名称
    public    $name = '小程序相关接口';
    protected $client = null;
    protected $app_id = null;
    protected $app_secret = null;
    protected $api_uri =  'https://api.weixin.qq.com/';
    
    protected $now = null;
    protected $qrcodeConfig = null;
    protected $access_token = null;
    protected $config = null;
    protected $settingModel = null;
    protected $setting = null;
    protected $xml = null;
    protected $wxBizMsgCrypt;
    
    protected $appModel = null;

    public function __construct(Setting $setting, BraveXml $xml) {
        $this->settingModel = $setting;
        $this->xml = $xml;
        $this->client = new Client();
        //获取系统设置
        $this->setting = $this->settingModel->getSettingByCategory("微信小程序");
        $this->setConfig($this->setting);
        
        $this->qrcodeConfig = config('code.qrcode');
        $this->now = date('Ymd');
    }
    
    /**
     * 设置微信小程序配置情报
     * @param type $app_id
     * @param type $app_secret
     */
    public function setConfig($config) {
        $this->config = $config;
        $this->app_id = $config['wx_app_id'];
        $this->app_secret = $config['wx_app_secret'];
        if (time() + 300 > $this->config['wx_access_token_expires_in']) {
            if ($this->getAccessToken() === false) {
                return false;
            }
        } else {
            $this->access_token = $this->config['wx_access_token'];
        }
    }
    
    /**
     * 随机生成指定长度字符串
     * @param type $length
     * @return type
     */
    public function getRandomStr($length = 32){
        $baseStr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
        $str = substr(str_shuffle($baseStr), 0, $length);
        return $str;
    }
    
    /**
     * 获取微信相关接口数据
     * 
     * @param type $path
     * @param array $data
     * @param type $method
     * @param type $format
     * @param type $header
     * @return boolean
     */
    public function getApiData($path, array $data = [], $method = 'POST', $format = null, $header = [])
    {
        $url = $this->api_uri . ltrim($path, '/');
        $method = strtoupper($method);
        
        $verify = true;
        if(preg_match("#^https#is", $url)) {
            $verify = false;
        }
        $opts = [
            'headers' => $header,
            'http_errors' => false,
            'timeout' => 20,
            'version' => 1.1,
        ];
        
        if(is_array($data) && count($data)) {
            $opts = array_merge($opts, $data);
        }
        
        $verify == false ? $opts['verify'] = false : '';
        $logData = [
            'method' => $method,
            'uri' => $url,
            'data' => $data
        ];
        $response = $this->client->request(
            $method,
            $url,
            $opts
        );
        if ($response->getStatusCode() != '200') {
            $logData['result'] = [
                'code' => $response->getStatusCode(), 
                'Response' => $response->getBody()
            ];
            BLogger::getLogger(BLogger::LOG_WX_API)->error($logData);
            return false;
        }
        $result = (string) $response->getBody();
        if($format == 'json' && preg_match('/^{.*}$/is', $result)) {
            $result = json_decode($result, true);
            //解析json数据
            if (json_last_error() != JSON_ERROR_NONE) {
                $logData['result'] = 'json error:' + json_last_error();
                BLogger::getLogger(BLogger::LOG_WX_API)->error($logData);
                return false;
            }
        }
        $logData['result'] = 'SUCCESS';
        BLogger::getLogger(BLogger::LOG_WX_API)->info($logData);
        return $result;
    }
    
    /**
     * 获取小程序的access_token
     * @return boolean
     */
    public function getAccessToken() {
        $path = "/cgi-bin/token";
        $data = [
            'grant_type' => 'client_credential',
            'appid' => $this->app_id,
            'secret' => $this->app_secret
        ];
        $method = 'GET';
        
        $result = $this->getApiData($path, ['query' => $data], $method, 'json');
        if($result === false) {
            return false;
        }
        
        if(!isset($result['access_token'])) {
            BLogger::getLogger(BLogger::LOG_WX_API)->info($result);
            return false;
        }
        $this->access_token = $result['access_token'];
        //更新数据库中 access_token 值以及有效期
        $this->settingModel->where('category', "微信小程序")->where('name', 'wx_access_token')->update([
            'value' => $this->access_token,
        ]);
        //更新数据库中 wx_access_token_expires_in 值以及有效期
        $this->settingModel->where('category', "微信小程序")->where('name', 'wx_access_token_expires_in')->update([
            'value' => time() + $result['expires_in']
        ]);
        
        return true;
    }
    
    /**
     * 检测access_token
     * @return boolean
     */
    public function checkAccessToken() {
        if (empty($this->access_token)) {
            $error = 'AccessToken不存在';
            BLogger::getLogger(BLogger::LOG_WX_API)->info($error);
            return false;
        }
        return true;
    }
    
    /**
     * 清空access_token
     */
    public function clearAccessToken() {
        $this->access_token = null;
    }
    
    /**
     * 生成小程序二维码
     * 
     * @param type $path
     * @param type $query
     * @param type $width
     */
    public function createWXAQRCode($path, $query, $width = 640)
    {
        if ($this->checkAccessToken() === false) {
            return false;
        }
        
        $uri = "/cgi-bin/wxaapp/createwxaqrcode?access_token=" . $this->access_token;
        $data = [
            'path' => $path . "?" . http_build_query($query),
            'width' => $width
        ];
        $method = 'POST';
        
        $header = [
            'cache-control' => 'no-cache', 
            'content-type' => 'application/json', 
            'Accept' => 'application/json'
        ];
        
        $result = $this->getApiData($uri, ['json' => $data], $method, 'json', $header);
        if($result === false) {
            return false;
        }
        
        if (isset($result['errcode'])) {
            BLogger::getLogger(BLogger::LOG_WX_API)->info($result);
            return false;
        }
        
        return $this->qrcode2file($result);
    }
    
    /**
     * 生成小程序码有限制
     * 
     * @param type $path
     * @param type $query
     * @param type $width
     */
    public function getWXACode($path, $query, $width = 640)
    {
        if ($this->checkAccessToken() === false) {
            return false;
        }
        
        $uri = "/wxa/getwxacode?access_token=" . $this->access_token;
        $data = [
            'path' => $path . "?" . http_build_query($query),
            'width' => $width,
            'is_hyaline' => true
        ];
        $method = 'POST';
        
        $header = [
            'cache-control' => 'no-cache', 
            'content-type' => 'application/json', 
            'Accept' => 'application/json'
        ];
        
        $result = $this->getApiData($uri, ['json' => $data], $method, 'json', $header);
        if($result === false) {
            return false;
        }
        
        if (isset($result['errcode'])) {
            BLogger::getLogger(BLogger::LOG_WX_API)->info($result);
            return false;
        }
        
        return $this->qrcode2file($result);
    }
    
    /**
     * 生成小程序码无限制
     * 
     * @param type $path
     * @param type $query
     * @param type $width
     */
    public function getWXACodeUnlimit($path, $query, $width = 640)
    {
        if ($this->checkAccessToken() === false) {
            return false;
        }
        
        $uri = "/wxa/getwxacodeunlimit?access_token=" . $this->access_token;
        $data = [
            'scene' => http_build_query($query),
//            'page' => $path,
            'width' => $width,
            'is_hyaline' => true //背景透明
        ];
        $method = 'POST';
        
        $header = [
            'cache-control' => 'no-cache', 
            'Content-Type' => 'application/json', 
            'Accept' => 'application/json'
        ];
        
        $result = $this->getApiData($uri, ['json' => $data], $method, 'json', $header);
        if($result === false) {
            return false;
        }
        
        if (isset($result['errcode'])) {
            BLogger::getLogger(BLogger::LOG_WX_API)->info($result);
            return false;
        }
        
        return $this->qrcode2file($result);
    }
    
    /**
     * save qrcode to file
     * @param type $qrBinary 二维码二进制数据
     * @return boolean|filepath
     */
    protected function qrcode2file ($qrBinary) {
        //检测目录
        $path = $this->qrcodeConfig['path'] . $this->now . '/';
        $dir = $this->qrcodeConfig['base'] . $path;
        if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
            BLogger::getLogger(BLogger::LOG_WX_API)->error('创建目录不成功，请检查['.$dir.']权限。');
            return false;
        }
        chmod($dir, 0755);
        //保存二维码
        $name = md5($this->getRandomStr(10) . time()) . '.png';
        
        $rs = file_put_contents($dir . $name, $qrBinary);
        if(!$rs) {
            BLogger::getLogger(BLogger::LOG_WX_API)->error('小程序码保存失败，请检查['.$dir.']权限。');
            return false;
        }
        
        return $path . $name;
    }
    
    
    /**
     * 公众号授权地址获取
     * @param type $redirect_uri
     */
    public function getWechatAuthorizeUri($redirect_uri = null) {
        $redirect_uri = empty($redirect_uri) ? route('wexinsign.callback') : $redirect_uri;
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->app_id."&redirect_uri=". urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=shop#wechat_redirect";
        return $url;
    }
    
    /**
     * 通过授权code 获取用户登录 access token
     * @param type $code
     * @return boolean
     */
    public function getUserAccessTokenByAuthorizeCode($code) {
        if (empty($code)) {
            return false;
        }
        $path = "/sns/oauth2/access_token";
        $data = [
            "appid" => $this->app_id,
            "secret" => $this->app_secret,
            "code" => $code,
            "grant_type" => "authorization_code"
        ];
        $method = 'GET';
        $format = 'json';
        $result = $this->getApiData($path, ['query' => $data], $method, $format);
        if($result === false) {
            return false;
        }
        if (!isset($result['access_token'])) {
            BLogger::getLogger(BLogger::LOG_WX_API)->info($result);
            return false;
        }
        return $result;
    }
    
    /**
     * 通过授权token 以及openid 获取用户信息
     * @param type $code
     * @return boolean
     */
    public function getUserInfoByAccessTokenAndOpenid($access_token, $openid, $lang = 'zh_CN') {
        $path = "/sns/userinfo";
        $data = [
            "openid" => $openid,
            "access_token" => $access_token,
            "lang" => $lang
        ];
        $method = 'GET';
        $format = 'json';
        $result = $this->getApiData($path, ['query' => $data], $method, $format);
        if($result === false) {
            return false;
        }
        if (isset($result['errcode'])) {
            BLogger::getLogger(BLogger::LOG_WX_API)->info($result);
            return false;
        }
        return $result;
    }
    
    /**
     * 通过小程序授权code 获取 小程序用户 openid
     * @param type $code
     * @return boolean
     */
    public function getOpenidByCode($code) {
        if (empty($code)) {
            return false;
        }
        $path = "/sns/jscode2session";
        $data = [
            "appid" => $this->app_id,
            "secret" => $this->app_secret,
            "js_code" => $code,
            "grant_type" => "authorization_code"
        ];
        $method = 'GET';
        $format = 'json';
        $result = $this->getApiData($path, ['query' => $data], $method, $format);
        if($result === false) {
            return false;
        }
        if (!isset($result['openid'])) {
            BLogger::getLogger(BLogger::LOG_WX_API)->info($result);
            return false;
        }
        return $result;
    }
}
