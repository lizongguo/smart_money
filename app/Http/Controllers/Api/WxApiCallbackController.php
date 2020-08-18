<?php
/**
 * callback Controller
 *
 * @package       Api.Controller
 * @author        yutlong
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) kbftech Inc.
 */

namespace App\Http\Controllers\Api;
use App\Libraries\BLogger;
use App\Repositories\Wechat\SmallProgramApiRepository;
use App\Models\Setting;
use App\Models\Application;
use Illuminate\Http\Request;

class WxApiCallbackController extends BaseController
{
    protected  $api, $setting, $app;
    public function __construct(Request $request, SmallProgramApiRepository $api, Setting $setting, Application $app)
    {
        parent::__construct($request);
        $this->api = $api;
        $this->setting = $setting;
        $this->app = $app;
    }
    
    /**
     * 第三方开发wx回调地址
     * @param Request $request
     */
    public function notify(Request $request)
    {
        $timestamp = $request->input('timestamp', null);
        $nonce = $request->input('nonce', null);
        $msg_signature = $request->input('msg_signature', null);
        $postData = file_get_contents('php://input');
        
        if(empty($timestamp) || empty($nonce) || empty($msg_signature) || empty($postData)) {
            exit("fail");
        }
        
        //解密内容
        $data = $this->api->decryptMsg($msg_signature, $timestamp, $nonce, $postData);
        BLogger::getLogger(BLogger::LOG_WX_CALLBACK)->info($data);
        if($data === false) {
            exit("fail");
        }
        switch ($data['InfoType']) {
            //第三方平台接口调用凭据
            case 'component_verify_ticket':
                $rs = $this->setting->where('name', 'component_verify_ticket')
                ->update(['value' => $data['ComponentVerifyTicket']]);
                if(!$rs) {
                    //保存失败
                    exit("fail");
                }
                break;
            //小程序 授权,修改授权回调
            case 'authorized':
            case 'updateauthorized':
                //查询时候已存在
                $result = $this->app->getAppByAuthorizerAppid($data['AuthorizerAppid']);
                if(!$result) {
                    exit("success");
                }
                //更新小程序
                $auth = $this->api->getAuthByAuthCode($data['AuthorizationCode']);
                if($auth === false) {
                    exit("fail");
                }
                $save = [
                    'authorizer_app_id' => $auth['authorizer_appid'],
                    'authorizer_access_token' => $auth['authorizer_refresh_token'],
                    'authorizer_refresh_token' => $auth['authorizer_refresh_token'],
                    'expires_in' => time() + $auth['expires_in'] - 5,
                    'func_info' => json_encode($auth['func_info']),
                ];
                $rs = $this->app->where('id', $request['id'])->update($save);
                if($rs === false) {
                    exit("fail");
                }
                break;
            case 'unauthorized':
                //查询时候已存在
                $result = $this->app->getAppByAuthorizerAppid($data['AuthorizerAppid']);
                if(!$result) {
                    exit("success");
                }
                //更新小程序
                $save = [
                    'authorization_flag' => 0
                ];
                $rs = $this->app->where('id', $request['id'])->update($save);
                if($rs === false) {
                    exit("fail");
                }
                break;
        }
        
        exit("success");
    }
    
}
