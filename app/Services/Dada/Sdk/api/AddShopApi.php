<?php
namespace App\Services\Dada\Sdk\api;
use App\Services\Dada\Sdk\config\UrlConfig;
/**
 * 添加门店api
 */

class AddShopApi extends BaseApi{
    
    public function __construct($params) {
        parent::__construct(UrlConfig::SHOP_ADD_URL, $params);
    }
}
