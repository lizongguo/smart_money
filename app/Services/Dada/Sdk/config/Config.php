<?php
namespace App\Services\Dada\Sdk\config;
class Config{
    
    /**
     * 达达开发者app_key
     */
    public $app_key = '';

    /**
     * 达达开发者app_secret
     */
    public $app_secret = '';

    /**
     * api版本
     */
    public $v = "1.0";

    /**
     * 数据格式
     */
    public $format = "json";

    /**
     * 商户ID
     */
    public $source_id;

    /**
     * host
     */
    public $host;

    /**
     * 构造函数
     */
    public function __construct($source_id, $app_key, $app_secret, $online){
        if ($online) {
            $this->source_id = $source_id;
            $this->host = "https://newopen.imdada.cn";
        } else {
            $this->source_id = "73753";
            $this->host = "http://newopen.qa.imdada.cn";
        }
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
    }

    public function getAppKey(){
        return $this->app_key;
    }

    public function getAppSecret(){
        return $this->app_secret;
    }

    public function getV(){
        return $this->v;
    }

    public function getFormat(){
        return $this->format;
    }

    public function getSourceId(){
        return $this->source_id;
    }

    public function getHost(){
        return $this->host;
    }
}
