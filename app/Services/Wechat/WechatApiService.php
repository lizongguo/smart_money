<?php
namespace App\Services\Wechat;

use App\Repositories\Wechat\SmallProgramApiRepository;
use App\Models\Setting;
use App\Libraries\BLogger;
/**
 * @class QrcodeService
 * @brief 获取桌面二维码
 * @date 2018/9/25 15:45:40
 */
class WechatApiService
{
    //服务名称
    public $name = '微信小程序接口相关的服务';
    
    protected $config = null;
    protected $desk = null;
    protected $application = null;
    protected $repository = null;
    protected $shopModel = null;
    protected $audit = null;
    protected $shopExtendModel = null;
    protected $settingModel = null;
    protected $limit = 100;
    protected $currentAppName = null;
    protected $currentApp = null;
    protected $qr_width = 0;
    protected $pagePath = 'pages/scan/scan';


    protected $appMap = null;

    public function __construct(
        SmallProgramApiRepository $repository,
        Setting $setting
    ) {
        $this->repository = $repository;
        $this->settingModel = $setting;
    }
    
    public function runRefreshToken() 
    {
        //刷新第3方平台自己token
        $data = $this->settingModel->whereExtend([
            'name' => 'wx_access_token_expires_in',
            'value' => ['conn' => "<=",  'value' => time() + 660]
        ])->first();
        
        if (!!$data) {
            $rs = $this->repository->getComponentAccessToken();
            BLogger::getLogger(BLogger::LOG_WX_API)->info("ComponentAccessToken refresh is " . ($rs ? "success." : 'fail.'));
        }
        
        $page = 1;
        //刷新授权小程序的授权token
        do{
            //查询所有有打印座位二维码的店铺情报
            $apps = $this->application->whereExtend([
                'type' => 2,
                'authorization_flag' => 1,
                'expires_in' => ['conn' => "<=",  'value' => time() + 660]
            ])->limit($this->limit)->offset(($page-1) * $this->limit)->get();
            if (!!$apps) {
                $apps = $apps->toArray();
            }
            $page++;
            foreach($apps as $app) {
                if (!$this->refreshAuthorizationToken($app)) {
                    continue;
                }
            }
            if (count($apps) < $this->limit) {
                break;
            }
        }while (true);
            
        return true;
    }
    
    
    /**
     * 刷新 authorizer_access_token 并保存数据
     * @param type $app
     */
    protected function refreshAuthorizationToken($app){
        //调用刷新 authorizer_access_token
        $authorization = $this->repository->refreshAuthorizationToken(
            $app['authorizer_app_id'], 
            $app['authorizer_refresh_token']
        );
        if ($authorization === false) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('app name['.$app['app_name'].'] refreshAuthorizationToken is fail.');
            return false;
        }
        
        $data = [
            'type' => 2,
            'authorizer_app_id' => $authorization['authorizer_appid'],
            'authorizer_access_token' => $authorization['authorizer_access_token'],
            'authorizer_refresh_token' => $authorization['authorizer_refresh_token'],
            'expires_in' => time() + $authorization['expires_in'] - 5,
            'func_info' => json_encode($authorization['func_info']),
            'authorization_flag' => 1,
        ];
        $result = $this->application->where('id' , $app['id'])->update($data);
        if ($result === false) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('app name['.$app['app_name'].'] refreshAuthorizationToken save fail.');
            return false;
        }
        BLogger::getLogger(BLogger::LOG_SCHEDULE)->info('app name['.$app['app_name'].'] refreshAuthorizationToken is success.');
        return true;
    }
    
    /**
     * 定时 检测，提交审核的小程序状态
     * @return boolean
     */
    public function checkAuditStatus() 
    {
        $page = 1;
        //刷新授权小程序的授权token
        do{
            //查询所有有打印座位二维码的店铺情报
            $apps = $this->application->whereExtend([
                'type' => 2,
                'authorization_flag' => 1
            ])->limit($this->limit)->offset(($page-1) * $this->limit)->get();
            if (!!$apps) {
                $apps = $apps->toArray();
            }
            $page++;
            foreach($apps as $app) {
                $rs = $this->repository->setConfig($app);
                // access token失败，跳出当次循环。
                if($rs === false){
                    $error = "获取小程序【{$app['app_name']}】access_token 失败了。";
                    BLogger::getLogger(BLogger::LOG_SCHEDULE)->error($error);
                    continue;
                }
                $result = $this->repository->getLatestAuditStatus();
                if (false === $result) {
                    continue;
                }
                $rs = $this->saveAuditStatus($result);
                if (false === $rs) {
                    continue;
                }
            }
            if (count($apps) < $this->limit) {
                break;
            }
        }while (true);
            
        return true;
    }
    
    /**
     * 保存审核状态
     * @param type $result
     */
    public function saveAuditStatus($audit_status) {
        $state = 3;
        if ($audit_status['status'] == 1) {
            $state = 98;
        } else if ($audit_status['status'] == 2) {
            $state = 2;
        }
        
        $save = [
            'state' => $state,
            'memo' => $state == 98 ? $audit_status['reason'] : '',
        ];
        
        $rs = $this->audit->where('auditid', $audit_status['auditid'])->update($save);
        if ($rs === false) {
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->error('审核ID:'.$audit_status['auditid'].',保存小程序的审核状态失败。');
            return false;
        }
        return true;
    }
    
}
