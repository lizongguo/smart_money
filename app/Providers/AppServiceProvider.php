<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Libraries\BraveSystem',
            function ($app) {
                return new \App\Libraries\BraveSystem();
            },
            true
        );
        $this->app->bind(
            'App\Libraries\BraveFields',
            function ($app) {
                return new \App\Libraries\BraveFields($app['App\Libraries\BraveSystem']);
            },
            true
        );
        $this->app->bind(
            'App\Libraries\BraveXml',
            function ($app) {
                return new \App\Libraries\BraveXml;
            },
            true
        );
        $this->app->bind(
            'App\Models\Attachment',
            function ($app) {
                return new \App\Models\Attachment;
            },
            true
        );
            
        $this->app->bind(
            'App\Models\Setting',
            function ($app) {
                return new \App\Models\Setting;
            },
            true
        );
            
        $this->app->bind(
            'App\Repositories\Wechat\SmallProgramApiRepository',
            function ($app) {
                return new \App\Repositories\Wechat\SmallProgramApiRepository(
                    $app['App\Models\Setting'],
                    $app['App\Libraries\BraveXml']
                );
            },
            true
        );
            
        //阿里大于
        $this->app->bind(
            'App\Services\Aliyun\Alidayu',
            function ($app) {
                return new \App\Services\Aliyun\AlidayuService();
            },
            true
        );
        
        $this->app->bind(
            'App\Services\Baidu\BaidusmsService',
            function ($app) {
                return new \App\Services\Baidu\BaidusmsService();
            },
            true
        );
            
        //Jpush
//        $this->app->bind(
//            'App\Services\Jpush\Jpush',
//            function ($app) {
//                return new \App\Services\Jpush\JpushService(
//                    $app['App\Models\Setting']
//                );
//            },
//            true
//        );
            
        $this->app->bind(
            'App\Services\Aliyun\Alipush',
            function ($app) {
                return new \App\Services\Aliyun\AlipushService();
            },
            true
        );
            
        //shop waiter
        $this->app->bind(
            'App\Models\Waiter',
            function ($app) {
                return new \App\Models\Waiter();
            },
            true
        );
        
        //order model
        $this->app->bind(
            'App\Models\Orders',
            function ($app) {
                return new \App\Models\Orders();
            },
            true
        );
            
        //Queue model
        $this->app->bind(
            'App\Models\Queue',
            function ($app) {
                return new \App\Models\Queue();
            },
            true
        );
        //OrderTakeout model
        $this->app->bind(
            'App\Models\OrderTakeout',
            function ($app) {
                return new \App\Models\OrderTakeout();
            },
            true
        );
            
        //pay order
        $this->app->bind(
            'App\Models\PayOrders',
            function ($app) {
                return new \App\Models\PayOrders();
            },
            true
        );
        
        
        //支付宝支付service
        $this->app->bind(
            'App\Services\Aliyun\Alipay',
            function ($app) {
                return new \App\Services\Aliyun\AlipayService(
                    $app['App\Models\PayOrders']
                );
            },
            true
        );
            
        //微信支付service
        $this->app->bind(
            'App\Services\Wechat\WechatPayService',
            function ($app) {
                return new \App\Services\Wechat\WechatPayService();
            },
            true
        );  
            
        //order push
        $this->app->bind(
            'App\Services\Order\OrderPushService',
            function ($app) {
                return new \App\Services\Order\OrderPushService(
                    $app['App\Services\Aliyun\Alipush'],
                    $app['App\Models\Waiter'],
                    $app['App\Models\Orders']
                );
            },
            true
        );
            
        //Queue push
        $this->app->bind(
            'App\Services\Order\QueuePushService',
            function ($app) {
                return new \App\Services\Order\QueuePushService(
                    $app['App\Services\Aliyun\Alipush'],
                    $app['App\Models\Waiter'],
                    $app['App\Models\Queue']
                );
            },
            true
        );
            
        //order refund
        $this->app->bind(
            'App\Services\Order\OrderRefundService',
            function ($app) {
                return new \App\Services\Order\OrderRefundService();
            },
            true
        );
            
        //order peisong service
//        $this->app->bind(
//            'App\Services\Meituan\Peisong',
//            function ($app) {
//                return new \App\Services\Meituan\PeisongService();
//            },
//            true
//        );
        //达达 配送
        $this->app->bind(
            'App\Services\Dada\DadaPeisong',
            function ($app) {
                return new \App\Services\Dada\DadaPeisongService();
            },
            true
        );
            
        //点我达 配送
        $this->app->bind(
            'App\Services\Dianwoda\DianwodaPeisongService',
            function ($app) {
                return new \App\Services\Dianwoda\DianwodaPeisongService();
            },
            true
        );
        
    }
}
