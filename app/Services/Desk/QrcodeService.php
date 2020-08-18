<?php
namespace App\Services\Desk;

use App\Repositories\Wechat\SmallProgramApiRepository;
use App\Services\Aliyun\Alipay;
use App\Models\Setting;
use App\Models\Desks;
use App\Models\Shops;
use App\Libraries\BLogger;
/**
 * @class QrcodeService
 * @brief 获取桌面二维码
 * @date 2018/9/25 15:45:40
 */
class QrcodeService
{
    //服务名称
    public $name = '获取桌面二维码';
    
    protected $config = null;
    protected $desk = null;
    protected $alipay = null;
    protected $repository = null;
    protected $limit = 100;
    protected $qr_width = 0;
    protected $pagePath = 'pages/index/main';
    
    protected $appMap = null;

    public function __construct(
        SmallProgramApiRepository $repository, 
        Alipay $alipay, 
        Desks $desk,
        Shops $shop
    ) {
        $this->repository = $repository;
        $this->alipay = $alipay;
        $this->desk = $desk;
        $this->shop = $shop;
        $this->qr_width = config('code.qrcode.width');
        $this->config = app()->make('App\Models\Setting')->getSystemSetting(true);
    }
    
    public function runDeskQrCode()
    {
        //获取店铺中微信 未生成的桌位
        if ($this->config['wx_app_state'] == 1) {
            $wxList = $this->desk->getList(['wx_state' => 0], true);
            foreach($wxList as $desk) {
                $this->dealDeskState('wechat', $desk);
            }
        }
        
        //获取店铺中支付宝 未生成的桌位
        if ($this->config['ali_app_state'] == 1) {
            $alipayList = $this->desk->getList(['ali_state' => 0], true);
            foreach($alipayList as $desk) {
                $this->dealDeskState('alipay', $desk);
            }
        }
        return true;
    }
    
    /**
     * 处理座位二维码问题
     * 
     * @param type $type alipay|wechat
     * @param type $desk
     * @return boolean
     */
    private function dealDeskState($type, $desk)
    {
        $query = [
            's' => $desk->shop_id,
            'd' => $desk->id
        ];
        //二维码
        if ($type == 'wechat') {
            //        $function = 'getWXACodeUnlimit';
//        $page = $this->pagePath;
//        if ($shop['shop_type'] == 2) {
//            //二维码
//            $function = 'createWXAQRCode';
//            $page = '/' . $this->pagePath;
//        }
            $function = 'createWXAQRCode';
            $page = $this->pagePath;
            
            //获取微信小程序二维码
            $path = $this->repository->$function($page, $query, $this->qr_width);
            $data = [
                'wx_qr_path' => $path,
                'wx_state' => "1"
            ];
        } elseif($type == 'alipay') {
            //获取支付宝小程序二维码
            $page = $this->pagePath; 
            $path = $this->alipay->createAlipayQrcode($page, $query, $this->desk->shop->shop_name . " 点餐小程序码");
            $data = [
                'ali_qr_path' => $path,
                'ali_state' => "1"
            ];
        }
        
        if($path === false) {
            $error = "店铺桌位ID【{$desk['id']}】,获取 {$type} 小程序二维码失败了 。";
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info($error);
            return false;
        }
        
        $result = $this->desk->where('id', $desk->id)->update($data);
        
        //二维码保存失败
        if ($result === false) {
            //写日志
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->error("座位二维码DESK ID【{$desk['id']}】{$type}二维码保存失败。");
            return false;
        }
        
        return true;
    }
    
    
    public function runShopQrCode() {
        //获取店铺中微信 未生成的桌位
        if ($this->config['wx_app_state'] == 1) {
            $wxList = $this->shop->where('shop_wx_qrcode', '')->get();
            foreach($wxList as $shop) {
                $this->dealShopQrCode('wechat', $shop);
            }
        }
        
        //获取店铺中支付宝 未生成的桌位
        if ($this->config['ali_app_state'] == 1) {
            $alipayList = $this->shop->where('shop_ali_qrcode', '')->get();
            foreach($alipayList as $shop) {
                $this->dealShopQrCode('alipay', $shop);
            }
        }
        return true;
    }
    
    /**
     * 处理店铺二维码问题
     * @param type $shop
     * @param type $shop
     * @return boolean
     */
    private function dealShopQrCode($type, $shop) {
        $query = [
            's' => $shop->id,
            't' => 'queue',
        ];
        //二维码
        if ($type == 'wechat') {
            
            $function = 'createWXAQRCode';
            $page = $this->pagePath;
            
            //获取微信小程序二维码
            $path = $this->repository->$function($page, $query, $this->qr_width);
            $data = [
                'shop_wx_qrcode' => $path
            ];
        } elseif($type == 'alipay') {
            //获取支付宝小程序二维码
            $page = $this->pagePath; 
            $path = $this->alipay->createAlipayQrcode($page, $query, $this->shop->shop_name . " 点餐小程序码");
            $data = [
                'shop_ali_qrcode' => $path
            ];
        }
        
        if($path === false) {
            $error = "店铺ID【{$shop->id}】,获取 {$type} 小程序二维码失败了 。";
            BLogger::getLogger(BLogger::LOG_SCHEDULE)->info($error);
            return false;
        }
        
        $result = $this->shop->where('id', $shop->id)->update($data);
        
        //二维码保存失败
        if ($result === false) {
            //写日志
            BLogger::getLogger(BLogger::LOG_WX_API)->error("店铺ID【{$shop->id}】{$type}二维码保存失败。");
            return false;
        }
        
        return true;
    }
}
