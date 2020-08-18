<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
use App\Libraries\BLogger;

/**
 *  API BaseController
 * @author yutlong
 * @date 2017-12-29 17:34:10
 */
class BaseController extends Controller
{
    protected $sep = "\r\n";
    protected $response = [];
    protected $back = [
        'status' => '200',
        'msg' => ''
    ];
    
    //认证用户情报
    protected $user = null;
    //认证标记
    protected $accessFlag = true;
    //用户model
    protected $userModel = null;
    
    //app model
    protected $app_name = '';
    protected $applicationModel = null;
    protected $application = null;

    public function __construct(Request $request)
    {
        $this->userModel = new Users;
        //AccessToken 用户获取
        $this->accessFlag = $this->getUserByAccessToken($request);
        //排除不验证access-token的控制器
        $noCheckController = [
            'callback', 
            'wxapicallback', 
            'upload', 
            'setting', 
            'user', 
            'shops',
            'waiter',
            'verification',
            'push',
            'test'
        ];
        
        if (!in_array($this->getControllerName(), $noCheckController)) {
//            //授权认证
            $this->middleware(function($request, $next) {
                //验证device type
                $device_type = $request->header('device-type', NULL);
                if (!in_array($device_type, ['alipay', 'wechat', 'ios', 'android'])) {
                    return ['status' => '410', 'msg' => 'deviceType参数缺失。'];
                }
                $flag = $this->authenticate();
                return $flag === true ? $next($request) : $flag;
            });
        }
        
        //api日志函数
        register_shutdown_function(array($this, 'log'));
    }
   
    
    public function getControllerName() {
        return strtolower(preg_replace('#^(.*)\\\([^\\\]+)Controller$#is', '$2', get_class($this)));
    }
    
    /**
     * 
     * @param Request $request
     * @return Boolean true|false
     */
    protected function getUserByAccessToken(Request $request) {
        $accessToken = $request->header('access-token', NULL);
        if(empty($accessToken)) {
            return false;
        }
        $deviceType = $request->header('device-type', NULL);
        $this->user = $this->userModel->getUserByAccessToken($accessToken, $deviceType);
        if($this->user === false) {
            return false;
        }
        return true;
    }
    
    /**
     * 认证是否通过， 不通过，返回错误情报，结束运行。
     */
    protected function authenticate() {
        if($this->accessFlag) {
            return true;
        }
        header('Content-Type:application/json; charset=utf-8');
        $this->response = ['status' => '410', 'msg' => '认证失效，请重新登录。'];
//        exit(json_encode($this->response));
        return response()->json($this->response);
    }
    
    /**
     *  data to jsonString
     * @param type $data
     * @param type $httpd_code
     * @return type
     */
    protected function dataToJson($data, $httpd_code = '200')
    {
        $this->response = $data;
        return response()->json($data, $httpd_code);
    }

    /**
     *  data to jsonString
     * @param type $data
     * @param type $httpd_code
     * @return type
     */
    protected function dataToJsonText($data, $httpd_code = '200')
    {
        $this->response = $data;
        return response()->json($data, $httpd_code)->header('Content-Type', 'text/plain; charset=utf-8');
    }
    
    /**
     * write api request log
     */
    public function log()
    {   
        if (config('app.api_log_flag') === false) {
            return false;
        }
        $log = $this->sep . 'URL:' . (url()->current()) . $this->sep;
        $log .= str_repeat('=', 15) . 'Request' . str_repeat('=', 16) . $this->sep;
        if (isset($_SERVER['HTTP_ACCESS_TOKEN'])) {
            $log .= 'access-token: ' . $_SERVER['HTTP_ACCESS_TOKEN'] . $this->sep;
        }
        
        if (count($_GET) > 0) {
            $log .= 'GET:' . $this->sep;
            $log .= print_r($_GET, true);
        }
        if (count($_POST) > 0) {
            $log .= 'POST:' . $this->sep;
            $log .= print_r($_POST, true);
        }
        if ($_FILES) {
            $log .= 'FILES:' . $this->sep;
            $log .= print_r($_FILES, true);
        }
        
        if(count($this->response)) {
            $log .= str_repeat('=', 15) . 'Response' . str_repeat('=', 15) . $this->sep;
            $log .= print_r($this->response, true) . $this->sep;
            $log .= str_repeat('=', 38) . $this->sep;
        }
        BLogger::getLogger(BLogger::LOG_API_RECORD)->info($log);
    }
    
}
